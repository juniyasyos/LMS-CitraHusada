<?php

namespace App\Services;

use App\Models\RestoreLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class RestoreService
{
    /**
     * Path folder temporary untuk ekstraksi backup.
     */
    protected string $tempDir;

    /**
     * Path backup storage sementara (untuk rollback storage).
     */
    protected string $storageBackupDir;

    /**
     * Nama file pre-restore backup yang dibuat otomatis.
     */
    protected ?string $preRestoreBackupFile = null;

    /**
     * Menjalankan proses restore lengkap.
     */
    public function restore(string $backupFile, int $userId): RestoreLog
    {
        // Naikkan batas waktu eksekusi untuk proses restore yang besar
        set_time_limit(600);

        $this->tempDir = storage_path('app/restore-temp/' . now()->format('YmdHis'));
        $this->storageBackupDir = storage_path('app/storage-backup-' . now()->format('YmdHis'));

        // Buat log awal
        $log = RestoreLog::create([
            'backup_file'        => $backupFile,
            'restored_by'        => $userId,
            'restore_started_at' => now(),
            'status'             => 'in_progress',
            'message'            => 'Proses restore dimulai...',
        ]);

        try {
            // =============================================
            // LANGKAH 1: Backup otomatis sebelum restore
            // =============================================
            Log::info('[Restore] Langkah 1: Membuat backup otomatis sebelum restore...');
            $this->preRestoreBackupFile = $this->createPreRestoreBackup();
            $log->update(['pre_restore_backup' => $this->preRestoreBackupFile]);

            // =============================================
            // LANGKAH 2: Extract file backup ZIP
            // =============================================
            Log::info('[Restore] Langkah 2: Mengekstrak file backup...');
            $extracted = $this->extractBackup($backupFile);

            // =============================================
            // LANGKAH 3: Restore Database
            // =============================================
            Log::info('[Restore] Langkah 3: Melakukan restore database...');
            $this->restoreDatabase($extracted['sql_file']);

            // =============================================
            // LANGKAH 4: Restore Storage
            // =============================================
            if ($extracted['storage_path']) {
                Log::info('[Restore] Langkah 4: Melakukan restore storage...');
                $this->restoreStorage($extracted['storage_path']);
            } else {
                Log::info('[Restore] Langkah 4: Tidak ada file storage dalam backup, dilewati.');
            }

            // =============================================
            // LANGKAH 5: Berhasil
            // =============================================
            $log->update([
                'status'              => 'success',
                'restore_finished_at' => now(),
                'message'             => 'Restore berhasil diselesaikan.',
            ]);

            Log::info('[Restore] Proses restore selesai dengan sukses.');

            return $log;

        } catch (\Exception $e) {
            Log::error('[Restore] Restore gagal: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // =============================================
            // ROLLBACK: Kembalikan ke kondisi sebelum restore
            // =============================================
            $rollbackMessage = '';
            try {
                if ($this->preRestoreBackupFile) {
                    Log::info('[Restore] Memulai rollback menggunakan backup pre-restore...');
                    $this->performRollback();
                    $rollbackMessage = ' Rollback berhasil dilakukan.';
                    $log->update([
                        'status'              => 'rolled_back',
                        'restore_finished_at' => now(),
                        'message'             => 'Restore gagal: ' . $e->getMessage() . $rollbackMessage,
                    ]);
                } else {
                    $log->update([
                        'status'              => 'failed',
                        'restore_finished_at' => now(),
                        'message'             => 'Restore gagal: ' . $e->getMessage() . ' (Tidak ada backup pre-restore untuk rollback)',
                    ]);
                }
            } catch (\Exception $rollbackException) {
                Log::error('[Restore] Rollback juga gagal: ' . $rollbackException->getMessage());
                $log->update([
                    'status'              => 'failed',
                    'restore_finished_at' => now(),
                    'message'             => 'Restore gagal: ' . $e->getMessage() . ' | Rollback juga gagal: ' . $rollbackException->getMessage(),
                ]);
            }

            return $log;

        } finally {
            // =============================================
            // CLEANUP: Hapus file temporary
            // =============================================
            $this->cleanup();
        }
    }

    /**
     * Membuat backup otomatis sebelum proses restore dimulai.
     * Menggunakan PHP PDO (sama seperti backup:database) agar tidak bergantung pada mysqldump.
     */
    protected function createPreRestoreBackup(): ?string
    {
        try {
            $appName = config('backup.backup.name', config('app.name', 'lms'));
            $timestamp = now()->format('Y-m-d-H-i-s');
            $zipName = "Pre_Restore_{$timestamp}.zip";
            $s3Path = "{$appName}/{$zipName}";

            $backupDir = sys_get_temp_dir() . '/pre_restore_' . uniqid();
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $dbName = config('database.connections.mysql.database', 'lms_db');
            $sqlFile = $backupDir . "/{$dbName}_dump.sql";
            $zipFile = $backupDir . '/' . $zipName;

            // Dump database menggunakan PHP PDO
            $this->dumpDatabaseViaPdo($sqlFile);

            // Buat ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Gagal membuat arsip ZIP pre-restore');
            }
            $zip->addFile($sqlFile, "database/{$dbName}_dump.sql");
            $zip->close();

            // Upload ke MinIO
            $diskName = config('backup.backup.destination.disks.0', env('FILESYSTEM_DISK', 's3'));
            Storage::disk($diskName)->put($s3Path, fopen($zipFile, 'r'));

            // Cleanup temp
            @unlink($sqlFile);
            @unlink($zipFile);
            @rmdir($backupDir);

            Log::info('[Restore] Pre-restore backup dibuat: ' . $s3Path);
            return $s3Path;

        } catch (\Exception $e) {
            Log::warning('[Restore] Gagal membuat pre-restore backup: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengekstrak file backup ZIP ke folder temporary.
     *
     * @return array{sql_file: string, storage_path: string|null}
     */
    protected function extractBackup(string $backupFile): array
    {
        $diskName = config('backup.backup.destination.disks.0', env('FILESYSTEM_DISK', 's3'));
        $disk = Storage::disk($diskName);

        if (!$disk->exists($backupFile)) {
            throw new \RuntimeException('File backup tidak ditemukan: ' . $backupFile);
        }

        // Buat folder temporary
        if (!File::isDirectory($this->tempDir)) {
            File::makeDirectory($this->tempDir, 0755, true);
        }

        // Download dari MinIO ke temporary lokal
        $zipPath = $this->tempDir . '/' . basename($backupFile);
        Log::info('[Restore] Mengunduh file backup dari MinIO (S3) ke temporary lokal...');
        file_put_contents($zipPath, $disk->get($backupFile));

        $zip = new ZipArchive();
        $result = $zip->open($zipPath);

        if ($result !== true) {
            throw new \RuntimeException('Gagal membuka file ZIP. Error code: ' . $result);
        }

        $zip->extractTo($this->tempDir);
        $zip->close();

        Log::info('[Restore] File backup berhasil diekstrak ke: ' . $this->tempDir);

        // Cari file SQL dump
        $sqlFile = $this->findSqlFile($this->tempDir);
        if (!$sqlFile) {
            throw new \RuntimeException('File SQL dump tidak ditemukan di dalam backup.');
        }

        // Cari folder storage
        $storagePath = $this->findStoragePath($this->tempDir);

        return [
            'sql_file'     => $sqlFile,
            'storage_path' => $storagePath,
        ];
    }

    /**
     * Mencari file .sql secara rekursif di dalam folder yang diekstrak.
     */
    protected function findSqlFile(string $directory): ?string
    {
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                Log::info('[Restore] File SQL ditemukan: ' . $file->getPathname());
                return $file->getPathname();
            }
        }

        return null;
    }

    /**
     * Mencari folder storage di dalam backup yang diekstrak.
     */
    protected function findStoragePath(string $directory): ?string
    {
        $searchPatterns = [
            $directory . '/storage/app/public',
            $directory . '/storage',
        ];

        foreach ($searchPatterns as $path) {
            if (File::isDirectory($path)) {
                Log::info('[Restore] Folder storage ditemukan: ' . $path);
                return $path;
            }
        }

        // Pencarian rekursif
        $directories = File::directories($directory);
        foreach ($directories as $dir) {
            $found = $this->findStorageRecursive($dir);
            if ($found) {
                return $found;
            }
        }

        Log::info('[Restore] Tidak menemukan folder storage di dalam backup.');
        return null;
    }

    /**
     * Pencarian rekursif folder storage/app/public.
     */
    protected function findStorageRecursive(string $directory, int $depth = 0): ?string
    {
        if ($depth > 10) return null;

        $basename = basename($directory);

        if ($basename === 'public') {
            $parent = basename(dirname($directory));
            $grandparent = basename(dirname(dirname($directory)));
            if ($parent === 'app' && $grandparent === 'storage') {
                Log::info('[Restore] Folder storage ditemukan (recursive): ' . $directory);
                return $directory;
            }
        }

        $subdirs = File::directories($directory);
        foreach ($subdirs as $subdir) {
            $found = $this->findStorageRecursive($subdir, $depth + 1);
            if ($found) {
                return $found;
            }
        }

        return null;
    }

    /**
     * Melakukan restore database dari file SQL dump menggunakan PHP PDO.
     * Tidak bergantung pada mysql CLI binary (menghindari masalah caching_sha2_password).
     */
    protected function restoreDatabase(string $sqlFilePath): void
    {
        Log::info('[Restore] Memulai restore database via PHP PDO...');

        // Drop semua tabel yang ada terlebih dahulu
        $this->dropAllTables();

        // Baca dan eksekusi file SQL via PDO
        $pdo = DB::connection()->getPdo();
        $sqlContent = file_get_contents($sqlFilePath);

        if ($sqlContent === false) {
            throw new \RuntimeException('Gagal membaca file SQL: ' . $sqlFilePath);
        }

        // Disable foreign key checks selama import
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $pdo->exec("SET NAMES utf8mb4");
        $pdo->exec("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'");

        // Pisahkan per statement (split by semicolon yang diikuti newline)
        // Ini lebih aman daripada split sederhana karena menghindari semicolon di dalam string
        $statements = $this->splitSqlStatements($sqlContent);

        $executedCount = 0;
        $errorCount = 0;

        foreach ($statements as $statement) {
            $statement = trim($statement);

            // Skip komentar dan baris kosong
            if (empty($statement) || str_starts_with($statement, '--') || str_starts_with($statement, '/*')) {
                continue;
            }

            try {
                $pdo->exec($statement);
                $executedCount++;
            } catch (\PDOException $e) {
                $errorCount++;
                // Log warning tapi lanjutkan (beberapa statement mungkin tidak kritikal)
                Log::warning('[Restore] SQL statement gagal: ' . substr($statement, 0, 100) . '... Error: ' . $e->getMessage());
            }
        }

        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        Log::info("[Restore] Database restore selesai. Eksekusi: {$executedCount} statement, Error: {$errorCount}");

        // Verifikasi tabel-tabel kritis ada
        $this->verifyDatabaseRestore();

        Log::info('[Restore] Database berhasil di-restore via PDO.');
    }

    /**
     * Split SQL dump menjadi statement-statement individual.
     * Menangani semicolon di dalam string values dengan benar.
     */
    protected function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];

            // Handle string literals
            if (!$inString && ($char === "'" || $char === '"')) {
                $inString = true;
                $stringChar = $char;
                $current .= $char;
                continue;
            }

            if ($inString && $char === $stringChar) {
                // Check for escaped quote
                if ($i + 1 < $length && $sql[$i + 1] === $stringChar) {
                    $current .= $char . $sql[$i + 1];
                    $i++;
                    continue;
                }
                // Check for backslash escape
                if ($i > 0 && $sql[$i - 1] === '\\') {
                    $current .= $char;
                    continue;
                }
                $inString = false;
                $current .= $char;
                continue;
            }

            // Semicolon outside string = end of statement
            if (!$inString && $char === ';') {
                $trimmed = trim($current);
                if (!empty($trimmed)) {
                    $statements[] = $trimmed;
                }
                $current = '';
                continue;
            }

            $current .= $char;
        }

        // Tambahkan statement terakhir jika ada
        $trimmed = trim($current);
        if (!empty($trimmed)) {
            $statements[] = $trimmed;
        }

        return $statements;
    }

    /**
     * Drop semua tabel di database.
     */
    protected function dropAllTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.mysql.database');
        $column = 'Tables_in_' . $databaseName;

        foreach ($tables as $table) {
            $tableName = $table->$column;
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
            Log::debug('[Restore] Dropped table: ' . $tableName);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Log::info('[Restore] Semua tabel berhasil di-drop.');
    }

    /**
     * Verifikasi bahwa tabel-tabel kritis ada setelah restore.
     */
    protected function verifyDatabaseRestore(): void
    {
        // Hanya cek tabel yang pasti ada di setiap backup (bukan sessions/jobs yang mungkin tidak ada)
        $criticalTables = ['users', 'roles'];
        $existingTables = collect(DB::select('SHOW TABLES'))->pluck(
            'Tables_in_' . config('database.connections.mysql.database')
        )->toArray();

        $missingTables = array_diff($criticalTables, $existingTables);

        if (!empty($missingTables)) {
            throw new \RuntimeException(
                'Verifikasi gagal: tabel kritis tidak ditemukan setelah restore: ' . implode(', ', $missingTables)
            );
        }

        Log::info('[Restore] Verifikasi database berhasil. Tabel kritis ditemukan: ' . implode(', ', $criticalTables));
    }

    /**
     * Melakukan restore storage (dari backup ke MinIO).
     */
    protected function restoreStorage(string $extractedStoragePath): void
    {
        $diskName = config('backup.backup.destination.disks.0', env('FILESYSTEM_DISK', 's3'));
        $disk = Storage::disk($diskName);
        $backupName = config('backup.backup.name', config('app.name', 'lms'));
        $storagePrefix = $backupName . '/storage';

        try {
            $uploadedCount = 0;
            $localFiles = File::allFiles($extractedStoragePath);

            foreach ($localFiles as $localFile) {
                $relativePath = $localFile->getRelativePathname();
                $relativePath = str_replace('\\', '/', $relativePath);
                $minioPath = $storagePrefix . '/' . $relativePath;

                $disk->put($minioPath, file_get_contents($localFile->getPathname()));
                $uploadedCount++;
            }

            Log::info('[Restore] ' . $uploadedCount . ' file storage berhasil di-upload ke MinIO.');

        } catch (\Exception $e) {
            Log::error('[Restore] Gagal restore storage ke MinIO: ' . $e->getMessage());
            throw new \RuntimeException('Gagal restore storage ke MinIO: ' . $e->getMessage());
        }
    }

    /**
     * Melakukan rollback menggunakan backup pre-restore.
     */
    protected function performRollback(): void
    {
        if (!$this->preRestoreBackupFile) {
            throw new \RuntimeException('Tidak ada backup pre-restore untuk rollback.');
        }

        Log::info('[Restore] Memulai rollback dari: ' . $this->preRestoreBackupFile);

        $rollbackTempDir = storage_path('app/rollback-temp/' . now()->format('YmdHis'));

        if (!File::isDirectory($rollbackTempDir)) {
            File::makeDirectory($rollbackTempDir, 0755, true);
        }

        $diskName = config('backup.backup.destination.disks.0', env('FILESYSTEM_DISK', 's3'));
        $disk = Storage::disk($diskName);

        // Download dari MinIO
        $zipPath = $rollbackTempDir . '/' . basename($this->preRestoreBackupFile);
        file_put_contents($zipPath, $disk->get($this->preRestoreBackupFile));

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException('Gagal membuka file backup pre-restore untuk rollback.');
        }

        $zip->extractTo($rollbackTempDir);
        $zip->close();

        // Restore database dari rollback (via PDO)
        $sqlFile = $this->findSqlFile($rollbackTempDir);
        if ($sqlFile) {
            $this->restoreDatabase($sqlFile);
            Log::info('[Restore] Database berhasil di-rollback.');
        }

        // Restore storage dari rollback
        $rollbackStoragePath = $this->findStoragePath($rollbackTempDir);
        if ($rollbackStoragePath) {
            $this->restoreStorage($rollbackStoragePath);
            Log::info('[Restore] Storage berhasil di-rollback.');
        }

        // Cleanup rollback temp
        if (File::isDirectory($rollbackTempDir)) {
            File::deleteDirectory($rollbackTempDir);
        }

        Log::info('[Restore] Rollback selesai.');
    }

    /**
     * Dump database menggunakan PHP PDO (untuk pre-restore backup).
     */
    protected function dumpDatabaseViaPdo(string $outputFile): void
    {
        $pdo = DB::connection()->getPdo();
        $dbName = config('database.connections.mysql.database');

        $handle = fopen($outputFile, 'w');
        if (!$handle) {
            throw new \RuntimeException("Gagal membuat file: {$outputFile}");
        }

        fwrite($handle, "-- PHP PDO Database Dump (Pre-Restore Backup)\n");
        fwrite($handle, "-- Database: {$dbName}\n");
        fwrite($handle, "-- Generated: " . now()->toDateTimeString() . "\n\n");
        fwrite($handle, "SET NAMES utf8mb4;\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n");
        fwrite($handle, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
            $createSql = $createStmt['Create Table'] ?? $createStmt['Create View'] ?? '';

            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
            fwrite($handle, $createSql . ";\n\n");

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
    }

    /**
     * Membersihkan file-file temporary.
     */
    protected function cleanup(): void
    {
        if (isset($this->tempDir) && File::isDirectory($this->tempDir)) {
            File::deleteDirectory($this->tempDir);
            Log::info('[Restore] Folder temporary berhasil dihapus: ' . $this->tempDir);
        }

        if (isset($this->storageBackupDir) && File::isDirectory($this->storageBackupDir)) {
            File::deleteDirectory($this->storageBackupDir);
            Log::info('[Restore] Folder storage backup sementara berhasil dihapus: ' . $this->storageBackupDir);
        }
    }
}
