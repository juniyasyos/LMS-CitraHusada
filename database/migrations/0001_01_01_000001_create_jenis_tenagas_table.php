<?php

/**
 * Migrasi: membuat tabel `jenis_tenagas`.
 *
 * Berisi tipe atau kategori tenaga kerja (mis. dokter, perawat, dsb.).
 * Struktur sederhana dengan nama jenis dan timestamp.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eksekusi migrasi membuat tabel `jenis_tenagas`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('jenis_tenagas', function (Blueprint $table) {
            $table->bigIncrements('jenis_tenaga_id');
            $table->string('jenis_tenaga');
            $table->timestamps();
        });
    }

    /**
     * Hapus tabel `jenis_tenagas` saat rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_tenagas');
    }
};
