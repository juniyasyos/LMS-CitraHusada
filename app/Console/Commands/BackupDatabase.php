<?php

namespace App\Console\Commands;

use App\Jobs\ProcessBackup;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--type=full : Tipe backup: database, files, atau full}';
    protected $description = 'Jalankan backup menggunakan PHP PDO (tanpa mysqldump) dan upload ke MinIO.';

    public function handle()
    {
        $type = $this->option('type');

        $this->info("🚀 Memulai backup (tipe: {$type})...");
        $this->info("   Menggunakan PHP PDO — tanpa mysqldump/mariadb-dump.");
        $this->newLine();

        // Jalankan Job secara langsung (sync) agar output terlihat di console
        try {
            $job = new ProcessBackup($type);
            $job->handle();

            $this->newLine();
            $this->info("✅ Proses backup selesai. Cek hasil di MinIO atau jalankan: php artisan backup:check-storage");
        } catch (\Throwable $e) {
            $this->error("❌ Backup gagal: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
