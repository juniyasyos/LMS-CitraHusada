<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE user_progress MODIFY COLUMN status ENUM('Belum Dimulai', 'Progres', 'Selesai', 'Gagal', 'Sesi Berakhir') DEFAULT 'Belum Dimulai'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE user_progress MODIFY COLUMN status ENUM('Belum Dimulai', 'Progres', 'Selesai', 'Gagal') DEFAULT 'Belum Dimulai'");

    }
};
