<?php

namespace App\Services;

use App\Models\RestoreLog;
use Illuminate\Support\Facades\Artisan;
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
     * Digunakan sebagai rollback safety jika restore gagal.
     */
    protected function createPreRestoreBackup(): ?string
    {
        // Catat file backup yang ada sebelum menjalankan backup baru
        $backupName = config('backup.backup.name', 'Laravel');
        $disk = Storage::disk('local');
        $existingFiles = collect($disk->allFiles($backupName))
            ->filter(fn($f) => str_ends_with($f, '.zip'))
            ->values()
            ->toArray();

        // Jalankan backup secara synchronous
        $exitCode = Artisan::call('backup:run', ['--disable-notifications' => true]);

        if ($exitCode !== 0) {
            Log::warning('[Restore] Pre-restore backup menghasilkan exit code: ' . $exitCode);
        }

        // Temukan file baru yang dibuat
        $allFiles = collect($disk->allFiles($backupName))
            ->filter(fn($f) => str_ends_with($f, '.zip'))
            ->values()
            ->toArray();

        $newFiles = array_diff($allFiles, $existingFiles);

        if (empty($newFiles)) {
            Log::warning('[Restore] Tidak dapat mengidentifikasi file pre-restore backup.');
            return null;
        }

        $preBackupFile = end($newFiles);
        Log::info('[Restore] Pre-restore backup dibuat: ' . $preBackupFile);

        return $preBackupFile;
    }

    /**
     * Mengekstrak file backup ZIP ke folder temporary.
     *
     * @return array{sql_file: string, storage_path: string|null}
     */
    protected function extractBackup(string $backupFile): array
    {
        $disk = Storage::disk('local');

        if (!$disk->exists($backupFile)) {
            throw new \RuntimeException('File backup tidak ditemukan: ' . $backupFile);
        }

        // Buat folder temporary
        if (!File::isDirectory($this->tempDir)) {
            File::makeDirectory($this->tempDir, 0755, true);
        }

        // Full path ke file ZIP
        $zipPath = $disk->path($backupFile);

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
     * Mencari folder storage/app/public di dalam backup yang diekstrak.
     * Spatie Backup menyimpan file dengan path relatif atau absolute di dalam ZIP.
     */
    protected function findStoragePath(string $directory): ?string
    {
        // Cari folder yang berisi 'storage/app/public' atau 'app/public'
        $searchPatterns = [
            $directory . '/storage/app/public',
            // Spatie mungkin menyimpan dengan path lengkap
        ];

        foreach ($searchPatterns as $path) {
            if (File::isDirectory($path)) {
                Log::info('[Restore] Folder storage ditemukan: ' . $path);
                return $path;
            }
        }

        // Pencarian rekursif untuk folder bernama 'public' di dalam path yang mengandung 'storage'
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
        if ($depth > 10) return null; // Batas kedalaman

        $basename = basename($directory);

        // Jika kita menemukan folder 'public' dan parent-nya 'app'
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
     * Melakukan restore database dari file SQL dump.
     */
    protected function restoreDatabase(string $sqlFilePath): void
    {
        $dbConfig = config('database.connections.mysql');
        $database = $dbConfig['database'];
        $host     = $dbConfig['host'];
        $port     = $dbConfig['port'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];

        // Path ke mysql binary (menggunakan dump_binary_path dari config)
        $mysqlBinaryPath = $dbConfig['dump']['dump_binary_path'] ?? '';
        $mysqlBin = $mysqlBinaryPath ? rtrim($mysqlBinaryPath, '/\\') . DIRECTORY_SEPARATOR . 'mysql' : 'mysql';

        // Pada Windows, tambahkan .exe
        if (PHP_OS_FAMILY === 'Windows') {
            $mysqlBin .= '.exe';
        }

        // Verifikasi mysql binary ada
        if ($mysqlBinaryPath && !file_exists($mysqlBin)) {
            throw new \RuntimeException('MySQL binary tidak ditemukan di: ' . $mysqlBin);
        }

        // Drop semua tabel yang ada terlebih dahulu
        $this->dropAllTables();

        // Bangun perintah mysql import
        $command = sprintf(
            '"%s" --protocol=tcp --host=%s --port=%s --user=%s %s %s < "%s"',
            $mysqlBin,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $password ? '--password=' . escapeshellarg($password) : '',
            escapeshellarg($database),
            $sqlFilePath
        );

        Log::info('[Restore] Menjalankan perintah import database...');

        // Eksekusi perintah
        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);

        if ($returnCode !== 0) {
            $errorOutput = implode("\n", $output);
            throw new \RuntimeException('Gagal mengimport database. Exit code: ' . $returnCode . '. Output: ' . $errorOutput);
        }

        // Verifikasi tabel-tabel kritis ada
        $this->verifyDatabaseRestore();

        Log::info('[Restore] Database berhasil di-restore.');
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
        $criticalTables = ['users', 'roles', 'materis', 'kategoris', 'sessions'];
        $existingTables = collect(DB::select('SHOW TABLES'))->pluck(
            'Tables_in_' . config('database.connections.mysql.database')
        )->toArray();

        $missingTables = array_diff($criticalTables, $existingTables);

        if (!empty($missingTables)) {
            throw new \RuntimeException(
                'Verifikasi gagal: tabel kritis tidak ditemukan setelah restore: ' . implode(', ', $missingTables)
            );
        }

        Log::info('[Restore] Verifikasi database berhasil. Semua tabel kritis ditemukan.');
    }

    /**
     * Melakukan restore storage (storage/app/public).
     */
    protected function restoreStorage(string $extractedStoragePath): void
    {
        $targetPath = storage_path('app/public');

        // Langkah 1: Backup storage aktif sementara
        if (File::isDirectory($targetPath)) {
            if (!File::isDirectory($this->storageBackupDir)) {
                File::makeDirectory($this->storageBackupDir, 0755, true);
            }
            File::copyDirectory($targetPath, $this->storageBackupDir);
            Log::info('[Restore] Storage aktif di-backup ke: ' . $this->storageBackupDir);
        }

        try {
            // Langkah 2: Hapus isi storage lama
            if (File::isDirectory($targetPath)) {
                File::cleanDirectory($targetPath);
                Log::info('[Restore] Isi storage lama berhasil dihapus.');
            } else {
                File::makeDirectory($targetPath, 0755, true);
            }

            // Langkah 3: Salin file dari backup
            File::copyDirectory($extractedStoragePath, $targetPath);
            Log::info('[Restore] File storage dari backup berhasil disalin.');

        } catch (\Exception $e) {
            // Langkah 4: Jika gagal, kembalikan storage lama
            Log::error('[Restore] Gagal restore storage: ' . $e->getMessage());

            if (File::isDirectory($this->storageBackupDir)) {
                if (File::isDirectory($targetPath)) {
                    File::cleanDirectory($targetPath);
                }
                File::copyDirectory($this->storageBackupDir, $targetPath);
                Log::info('[Restore] Storage lama berhasil dikembalikan dari backup sementara.');
            }

            throw new \RuntimeException('Gagal restore storage: ' . $e->getMessage());
        }

        // Pastikan symlink storage masih ada
        $this->ensureStorageLink();
    }

    /**
     * Pastikan symlink public/storage tetap ada.
     */
    protected function ensureStorageLink(): void
    {
        $publicStorageLink = public_path('storage');
        $targetPath = storage_path('app/public');

        if (!file_exists($publicStorageLink)) {
            try {
                Artisan::call('storage:link');
                Log::info('[Restore] Symlink storage berhasil di-recreate.');
            } catch (\Exception $e) {
                Log::warning('[Restore] Gagal membuat symlink storage: ' . $e->getMessage());
            }
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

        // Extract pre-restore backup
        $rollbackTempDir = storage_path('app/rollback-temp/' . now()->format('YmdHis'));

        if (!File::isDirectory($rollbackTempDir)) {
            File::makeDirectory($rollbackTempDir, 0755, true);
        }

        $disk = Storage::disk('local');
        $zipPath = $disk->path($this->preRestoreBackupFile);

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException('Gagal membuka file backup pre-restore untuk rollback.');
        }

        $zip->extractTo($rollbackTempDir);
        $zip->close();

        // Restore database dari rollback
        $sqlFile = $this->findSqlFile($rollbackTempDir);
        if ($sqlFile) {
            $this->restoreDatabase($sqlFile);
            Log::info('[Restore] Database berhasil di-rollback.');
        }

        // Restore storage dari backup sementara (lebih reliable daripada dari ZIP)
        $targetPath = storage_path('app/public');
        if (File::isDirectory($this->storageBackupDir)) {
            if (File::isDirectory($targetPath)) {
                File::cleanDirectory($targetPath);
            }
            File::copyDirectory($this->storageBackupDir, $targetPath);
            Log::info('[Restore] Storage berhasil di-rollback dari backup sementara.');
        }

        // Cleanup rollback temp
        if (File::isDirectory($rollbackTempDir)) {
            File::deleteDirectory($rollbackTempDir);
        }

        Log::info('[Restore] Rollback selesai.');
    }

    /**
     * Membersihkan file-file temporary.
     */
    protected function cleanup(): void
    {
        // Hapus folder temporary extract
        if (isset($this->tempDir) && File::isDirectory($this->tempDir)) {
            File::deleteDirectory($this->tempDir);
            Log::info('[Restore] Folder temporary berhasil dihapus: ' . $this->tempDir);
        }

        // Hapus folder backup storage sementara
        if (isset($this->storageBackupDir) && File::isDirectory($this->storageBackupDir)) {
            File::deleteDirectory($this->storageBackupDir);
            Log::info('[Restore] Folder storage backup sementara berhasil dihapus: ' . $this->storageBackupDir);
        }
    }
}
