<?php

namespace App\Jobs;

use App\Models\BackupLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ProcessBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 1;

    /**
     * Timeout in seconds (30 minutes).
     */
    public int $timeout = 1800;

    protected string $type;

    public function __construct(string $type = 'full')
    {
        $this->type = $type;
    }

    public function handle(): void
    {
        $timestamp = now()->format('Y-m-d_His');
        $typeLabel = match ($this->type) {
            'database' => 'Database',
            'files'    => 'Files',
            default    => 'Full',
        };
        $zipName = "{$typeLabel}_Backup_{$timestamp}.zip";
        $appName = config('backup.backup.name', config('app.name', 'lms'));
        $s3Path = "{$appName}/{$zipName}";

        try {
            $backupDir = sys_get_temp_dir() . '/backups_' . uniqid();
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            Log::info("Backup dimulai: {$zipName} (tipe: {$this->type})");

            $zipFile = match ($this->type) {
                'database' => $this->backupDatabase($backupDir, $zipName),
                'files'    => $this->backupFiles($backupDir, $zipName),
                default    => $this->backupFull($backupDir, $zipName),
            };

            // Upload ZIP ke MinIO (S3)
            $diskName = config('backup.backup.destination.disks.0', env('FILESYSTEM_DISK', 's3'));
            Storage::disk($diskName)->put($s3Path, fopen($zipFile, 'r'));

            $fileSize = filesize($zipFile);

            // Cleanup file sementara
            @unlink($zipFile);
            $this->cleanupTempDir($backupDir);

            // Catat ke backup_logs
            BackupLog::create([
                'filename' => $s3Path,
                'status'   => 'success',
                'size'     => $fileSize,
                'message'  => "Backup {$typeLabel} berhasil (via PHP PDO)",
            ]);

            Log::info("Backup selesai: {$s3Path}", ['size' => $fileSize]);

        } catch (\Throwable $e) {
            // Catat kegagalan
            BackupLog::create([
                'filename' => $s3Path ?? $zipName,
                'status'   => 'failed',
                'size'     => 0,
                'message'  => 'Gagal: ' . $e->getMessage(),
            ]);

            Log::error("Backup gagal: {$zipName}", ['error' => $e->getMessage()]);

            // Cleanup
            if (isset($zipFile) && file_exists($zipFile)) {
                @unlink($zipFile);
            }
            if (isset($backupDir)) {
                $this->cleanupTempDir($backupDir);
            }
        }
    }

    /**
     * Backup database menggunakan PHP PDO (tanpa mysqldump).
     */
    protected function backupDatabase(string $backupDir, string $zipName): string
    {
        $sqlFile = $backupDir . '/' . pathinfo($zipName, PATHINFO_FILENAME) . '.sql';
        $zipFile = $backupDir . '/' . $zipName;

        $this->dumpDatabaseViaPdo($sqlFile);

        // ZIP the SQL file
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Gagal membuat arsip ZIP');
        }
        $zip->addFile($sqlFile, basename($sqlFile));
        $zip->close();

        @unlink($sqlFile);
        return $zipFile;
    }

    /**
     * Backup storage files dari MinIO (S3).
     */
    protected function backupFiles(string $backupDir, string $zipName): string
    {
        $zipFile = $backupDir . '/' . $zipName;

        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Gagal membuat arsip ZIP');
        }

        $this->addS3FilesToZip($zip, 'storage');
        $zip->close();

        return $zipFile;
    }

    /**
     * Backup database + files (full backup).
     */
    protected function backupFull(string $backupDir, string $zipName): string
    {
        $database = config('database.connections.mysql.database', 'lms_db');
        $sqlFile = $backupDir . '/temp_db_dump.sql';
        $zipFile = $backupDir . '/' . $zipName;

        // 1. Dump database via PDO
        $this->dumpDatabaseViaPdo($sqlFile);

        // 2. Create ZIP with DB dump + S3 files
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Gagal membuat arsip ZIP');
        }

        // Tambahkan SQL dump
        $zip->addFile($sqlFile, 'database/' . $database . '_dump.sql');

        // Tambahkan file dari S3/MinIO
        $this->addS3FilesToZip($zip, 'storage');

        $zip->close();

        @unlink($sqlFile);
        return $zipFile;
    }

    /**
     * Dump seluruh database menggunakan PHP PDO (tanpa mysqldump/mariadb-dump).
     * PHP mysqlnd mendukung caching_sha2_password secara native.
     */
    protected function dumpDatabaseViaPdo(string $outputFile): void
    {
        $pdo = DB::connection()->getPdo();
        $dbName = config('database.connections.mysql.database');

        $handle = fopen($outputFile, 'w');
        if (!$handle) {
            throw new \RuntimeException("Gagal membuat file: {$outputFile}");
        }

        // Header
        fwrite($handle, "-- PHP PDO Database Dump\n");
        fwrite($handle, "-- Database: {$dbName}\n");
        fwrite($handle, "-- Generated: " . now()->toDateTimeString() . "\n\n");
        fwrite($handle, "SET NAMES utf8mb4;\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n");
        fwrite($handle, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

        // Ambil daftar tabel
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

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
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
        fclose($handle);

        Log::info("Database dump selesai: " . count($tables) . " tabel, " . round(filesize($outputFile) / 1024 / 1024, 2) . " MB");
    }

    /**
     * Download semua file dari S3 (MinIO) dan tambahkan ke arsip ZIP.
     */
    protected function addS3FilesToZip(ZipArchive $zip, string $zipPrefix): void
    {
        $diskName = config('backup.backup.destination.disks.0', env('FILESYSTEM_DISK', 's3'));
        $appName = config('backup.backup.name', config('app.name', 'lms'));
        $allFiles = Storage::disk($diskName)->allFiles();

        foreach ($allFiles as $file) {
            // Skip folder backup sendiri agar tidak recursive
            if (str_starts_with($file, $appName . '/') || str_starts_with($file, 'backups/')) {
                continue;
            }

            try {
                $contents = Storage::disk($diskName)->get($file);
                if ($contents !== null) {
                    $zip->addFromString($zipPrefix . '/' . $file, $contents);
                }
            } catch (\Throwable $e) {
                Log::warning("Backup: skip file {$file}: {$e->getMessage()}");
            }
        }
    }

    /**
     * Hapus direktori sementara secara rekursif.
     */
    protected function cleanupTempDir(string $dir): void
    {
        if (!is_dir($dir)) return;

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $file->isDir() ? @rmdir($file->getPathname()) : @unlink($file->getPathname());
        }

        @rmdir($dir);
    }
}
