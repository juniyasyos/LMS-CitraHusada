<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class CheckBackupStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:check-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan pengecekan apakah disk konfigurasi backup berhasil tersambung ke MinIO dan melihat file di dalamnya.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=========================================");
        $this->info("🔍 MEMULAI PENGECEKAN STORAGE BACKUP 🔍");
        $this->info("=========================================");

        // 1. Cek Disk yang Aktif
        $backupDisks = config('backup.backup.destination.disks');
        if (empty($backupDisks)) {
            $this->error("❌ ERROR: Konfigurasi disk backup kosong! Cek config/backup.php");
            return;
        }

        $activeDisk = $backupDisks[0];
        $this->info("1. Disk Target Backup: [{$activeDisk}]");

        // Tampilkan konfigurasi S3/MinIO jika disk adalah S3
        if ($activeDisk === 's3' || config("filesystems.disks.{$activeDisk}.driver") === 's3') {
            $this->line("   Driver   : s3");
            $this->line("   Endpoint : " . config("filesystems.disks.{$activeDisk}.endpoint"));
            $this->line("   Bucket   : " . config("filesystems.disks.{$activeDisk}.bucket"));
        }

        // 2. Tes Koneksi (Write & Read)
        $this->info("\n⏳ Menguji koneksi R/W ke {$activeDisk} (MinIO)...");
        $testFile = "test-connection-" . time() . ".txt";
        
        try {
            $disk = Storage::disk($activeDisk);
            $backupName = config('backup.backup.name', 'Laravel');
            $this->info("2. Folder Target Backup: [{$backupName}/]");

            $disk->put($testFile, "Koneksi MinIO Sukses!");
            $this->info("   ✅ Tes Upload Berhasil!");

            $content = $disk->get($testFile);
            if ($content === "Koneksi MinIO Sukses!") {
                $this->info("   ✅ Tes Download Berhasil!");
            }

            $disk->delete($testFile);
            $this->info("   ✅ Tes Hapus Berhasil!");

        } catch (\Exception $e) {
            $this->error("❌ KONEKSI GAGAL!");
            $this->error("Error: " . $e->getMessage());
            $this->line("Saran: Pastikan AWS_ENDPOINT, AWS_ACCESS_KEY_ID, dan kredensial lainnya di .env sudah benar.");
            return;
        }

        // 3. Cek File Backup yang Ada
        $this->info("\n⏳ Mencari file backup (*.zip) di {$activeDisk}...");
        try {
            $disk = Storage::disk($activeDisk);
            $files = collect($disk->allFiles($backupName))
                ->filter(fn($file) => str_ends_with($file, '.zip'))
                ->values();

            if ($files->isEmpty()) {
                $this->warn("   ⚠️ Belum ada file backup ZIP yang tersimpan di dalam folder {$backupName}/ pada MinIO.");
            } else {
                $this->info("   🎉 Ditemukan " . $files->count() . " file backup:");
                foreach ($files as $file) {
                    $size = number_format($disk->size($file) / 1048576, 2);
                    $date = date('Y-m-d H:i:s', $disk->lastModified($file));
                    $this->line("      - {$file} ({$size} MB) -> Dibuat: {$date}");
                }
            }
        } catch (\Exception $e) {
            $this->error("❌ Gagal membaca direktori {$backupName} di {$activeDisk}: " . $e->getMessage());
        }

        $this->info("\n=========================================");
        $this->info("✅ PENGECEKAN SELESAI ✅");
        $this->info("=========================================");
    }
}
