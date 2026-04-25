<?php

/**
 * Migrasi: membuat tabel `unit_kerjas`.
 *
 * Menyimpan nama-nama unit kerja yang tersedia dalam sistem.
 * Hanya menyediakan kolom identitas dan nama, ditambah timestamps.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel `unit_kerjas`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('unit_kerjas', function (Blueprint $table) {
            $table->bigIncrements('unit_kerja_id');
            $table->string('unit_kerja');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel `unit_kerjas` saat rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_kerjas');
    }
};
