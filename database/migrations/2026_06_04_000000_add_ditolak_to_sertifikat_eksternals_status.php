<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `sertifikat_eksternals` MODIFY `status` ENUM('Belum Disetujui', 'Disetujui', 'Tidak Disetujui', 'Ditolak') NOT NULL DEFAULT 'Belum Disetujui'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `sertifikat_eksternals` MODIFY `status` ENUM('Belum Disetujui', 'Disetujui', 'Tidak Disetujui') NOT NULL DEFAULT 'Belum Disetujui'");
    }
};
