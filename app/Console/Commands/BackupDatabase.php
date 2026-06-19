<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\BackupLog;
use ZipArchive;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--only-db : Hanya backup database tanpa file}';
    protected $description = 'Backup database menggunakan PHP PDO (tanpa mysqldump) dan upload ke MinIO.';

    public function handle()
    {
        $startTime = microtime(true);
        $this->info("🚀 Memulai proses backup via PHP PDO...");

        $appName = config('backup.backup.name', config('app.name', 'lms'));
        $timestamp = now()->format('Y-m-d-H-i-s');
        $zipFilename = "{$appName}/{$timestamp}.zip";
        $tempDir = storage_path('app/backup-temp/' . $timestamp);
        $tempZipPath = storage_path("app/backup-temp/{$timestamp}.zip");

        try {
            // Buat direktori sementara
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // ============================================
            // STEP 1: Dump database menggunakan PHP PDO
            // ============================================
            $this->info("📦 Dumping database via PDO...");
            $sqlFile = $tempDir . '/db-dumps/mysql-' . config('database.connections.mysql.database', 'lms_db') . '.sql';

            if (!is_dir(dirname($sqlFile))) {
                mkdir(dirname($sqlFile), 0755, true);
            }

            $this->dumpDatabaseViaPdo($sqlFile);
            $this->info("   ✅ Database dump selesai: " . $this->formatBytes(filesize($sqlFile)));

            // ============================================
            // STEP 2: Kumpulkan file-file (jika bukan --only-db)
            // ============================================
            if (!$this->option('only-db')) {
                $this->info("📁 Mengumpulkan file-file aplikasi...");
                $fileSources = config('backup.backup.source.files.include', []);
                $fileCount = 0;

                foreach ($fileSources as $source) {
                    if (is_dir($source)) {
                        $iterator = new \RecursiveIteratorIterator(
                            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                            \RecursiveIteratorIterator::LEAVES_ONLY
                        );

                        foreach ($iterator as $file) {
                            if ($file->isFile()) {
                                $fileCount++;
                            }
                        }
                    }
                }
                $this->info("   📊 Ditemukan {$fileCount} file untuk di-backup.");
            }

            // ============================================
            // STEP 3: Buat file ZIP
            // ============================================
            $this->info("🗜️  Membuat arsip ZIP...");
            $zip = new ZipArchive();

            if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \Exception("Gagal membuat file ZIP di {$tempZipPath}");
            }

            // Tambahkan SQL dump ke ZIP
            $zip->addFile($sqlFile, 'db-dumps/mysql-' . config('database.connections.mysql.database', 'lms_db') . '.sql');

            // Tambahkan file-file aplikasi ke ZIP (jika bukan --only-db)
            if (!$this->option('only-db')) {
                foreach ($fileSources as $source) {
                    if (is_dir($source)) {
                        $basePath = base_path();
                        $iterator = new \RecursiveIteratorIterator(
                            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                            \RecursiveIteratorIterator::LEAVES_ONLY
                        );

                        foreach ($iterator as $file) {
                            if ($file->isFile()) {
                                $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getRealPath());
                                $relativePath = str_replace('\\', '/', $relativePath);
                                $zip->addFile($file->getRealPath(), $relativePath);
                            }
                        }
                    }
                }
            }

            $zip->close();
            $zipSize = filesize($tempZipPath);
            $this->info("   ✅ ZIP dibuat: " . $this->formatBytes($zipSize));

            // ============================================
            // STEP 4: Upload ke MinIO (S3)
            // ============================================
            $this->info("☁️  Mengupload ke MinIO...");
            $diskName = config('backup.backup.destination.disks.0', env('FILESYSTEM_DISK', 's3'));
            $disk = Storage::disk($diskName);

            $disk->put($zipFilename, fopen($tempZipPath, 'r'));
            $this->info("   ✅ Upload selesai ke disk [{$diskName}] → {$zipFilename}");

            // ============================================
            // STEP 5: Catat ke database (BackupLog)
            // ============================================
            try {
                BackupLog::create([
                    'filename' => $zipFilename,
                    'status' => 'success',
                    'size' => $this->formatBytes($zipSize),
                    'message' => 'Backup berhasil (via PHP PDO)',
                ]);
            } catch (\Exception $e) {
                $this->warn("   ⚠️ Gagal mencatat log: " . $e->getMessage());
            }

            // ============================================
            // STEP 6: Bersihkan file sementara
            // ============================================
            $this->cleanup($tempDir, $tempZipPath);

            $elapsed = round(microtime(true) - $startTime, 2);
            $this->info("");
            $this->info("🎉 BACKUP BERHASIL! ({$elapsed} detik)");
            $this->info("   📍 Lokasi  : {$diskName}::{$zipFilename}");
            $this->info("   📦 Ukuran  : " . $this->formatBytes($zipSize));

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ BACKUP GAGAL: " . $e->getMessage());

            // Catat kegagalan
            try {
                BackupLog::create([
                    'filename' => $zipFilename ?? 'unknown',
                    'status' => 'failed',
                    'size' => '0 B',
                    'message' => 'Gagal: ' . $e->getMessage(),
                ]);
            } catch (\Exception $logEx) { /* silent */ }

            // Bersihkan
            if (isset($tempDir, $tempZipPath)) {
                $this->cleanup($tempDir, $tempZipPath);
            }

            return Command::FAILURE;
        }
    }

    /**
     * Dump seluruh database menggunakan PHP PDO (tanpa mysqldump/mariadb-dump)
     */
    private function dumpDatabaseViaPdo(string $outputFile): void
    {
        $pdo = DB::connection()->getPdo();
        $dbName = config('database.connections.mysql.database');

        $handle = fopen($outputFile, 'w');
        if (!$handle) {
            throw new \Exception("Gagal membuka file untuk menulis: {$outputFile}");
        }

        // Header
        fwrite($handle, "-- PHP PDO Database Dump\n");
        fwrite($handle, "-- Database: {$dbName}\n");
        fwrite($handle, "-- Generated: " . now()->toDateTimeString() . "\n");
        fwrite($handle, "-- Server: " . config('database.connections.mysql.host') . "\n\n");
        fwrite($handle, "SET NAMES utf8mb4;\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n");
        fwrite($handle, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

        // Ambil daftar tabel
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        $totalTables = count($tables);
        $this->info("   📋 Ditemukan {$totalTables} tabel.");

        $bar = $this->output->createProgressBar($totalTables);
        $bar->start();

        foreach ($tables as $table) {
            // CREATE TABLE statement
            $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
            $createSql = $createStmt['Create Table'] ?? $createStmt['Create View'] ?? '';

            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "-- Table: `{$table}`\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
            fwrite($handle, $createSql . ";\n\n");

            // INSERT statements (batch per 500 rows)
            $countResult = $pdo->query("SELECT COUNT(*) FROM `{$table}`")->fetchColumn();

            if ($countResult > 0) {
                $batchSize = 500;
                $offset = 0;

                // Ambil nama kolom
                $columns = $pdo->query("SHOW COLUMNS FROM `{$table}`")->fetchAll(\PDO::FETCH_COLUMN);
                $columnList = implode('`, `', $columns);

                while ($offset < $countResult) {
                    $rows = $pdo->query("SELECT * FROM `{$table}` LIMIT {$batchSize} OFFSET {$offset}")->fetchAll(\PDO::FETCH_ASSOC);

                    if (empty($rows)) break;

                    $values = [];
                    foreach ($rows as $row) {
                        $escaped = array_map(function ($val) use ($pdo) {
                            if (is_null($val)) return 'NULL';
                            return $pdo->quote($val);
                        }, array_values($row));
                        $values[] = '(' . implode(', ', $escaped) . ')';
                    }

                    fwrite($handle, "INSERT INTO `{$table}` (`{$columnList}`) VALUES\n");
                    fwrite($handle, implode(",\n", $values) . ";\n\n");

                    $offset += $batchSize;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
        fclose($handle);
    }

    /**
     * Hapus file dan direktori sementara
     */
    private function cleanup(string $tempDir, string $tempZipPath): void
    {
        // Hapus direktori sementara
        if (is_dir($tempDir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($iterator as $item) {
                $item->isDir() ? rmdir($item->getRealPath()) : unlink($item->getRealPath());
            }
            rmdir($tempDir);
        }

        // Hapus file ZIP sementara
        if (file_exists($tempZipPath)) {
            unlink($tempZipPath);
        }
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
