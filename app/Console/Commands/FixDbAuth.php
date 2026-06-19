<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDbAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fix-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaiki masalah caching_sha2_password dengan mengubah plugin autentikasi user database menjadi mysql_native_password.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Menyiapkan perbaikan autentikasi MySQL...");

        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        
        try {
            $this->info("Mencoba mengubah metode autentikasi untuk user '{$user}'...");
            
            // Alter user untuk localhost
            DB::statement("ALTER USER '{$user}'@'localhost' IDENTIFIED WITH mysql_native_password BY '{$password}';");
            $this->info("✅ Berhasil mengubah untuk localhost!");

            // Alter user untuk sembarang host (%) - biasanya yang dipakai di Docker
            try {
                DB::statement("ALTER USER '{$user}'@'%' IDENTIFIED WITH mysql_native_password BY '{$password}';");
                $this->info("✅ Berhasil mengubah untuk '%' (Docker Network)!");
            } catch (\Exception $e) {
                // Abaikan jika host % tidak ada
                $this->warn("   Catatan: Host '%' tidak ditemukan atau tidak diizinkan, diabaikan.");
            }

            DB::statement("FLUSH PRIVILEGES;");
            $this->info("\n🎉 Sukses! Plugin autentikasi telah diubah ke mysql_native_password.");
            $this->line("Sekarang Anda bisa menjalankan 'php artisan backup:run' kembali.");

        } catch (\Exception $e) {
            $this->error("❌ GAGAL: " . $e->getMessage());
            $this->warn("Saran: Pastikan user yang login (di .env) memiliki hak akses ALTER USER, atau jalankan perintah SQL ini secara manual di database Anda:");
            $this->line("ALTER USER '{$user}'@'%' IDENTIFIED WITH mysql_native_password BY '{$password}';");
        }
    }
}
