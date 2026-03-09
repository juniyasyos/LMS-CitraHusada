<?php

/**
 * Migrasi: membuat tabel pivot `materi_jenis_tenagas`.
 *
 * Menghubungkan materi dengan jenis tenaga yang dapat mengaksesnya.
 * Tabel pivot sederhana dengan foreign key ke `jenis_tenagas` dan
 * `materis`, kedua relasi menggunakan cascade delete.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel pivot untuk relasi banyak-ke-banyak.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('materi_jenis_tenagas', function (Blueprint $table) {
            $table->bigIncrements('materi_jenis_tenaga_id');
            $table->unsignedBigInteger('jenis_tenaga_id');
            $table->unsignedBigInteger('materi_id');
            $table->timestamps();

            $table->foreign('jenis_tenaga_id')->references('jenis_tenaga_id')->on('jenis_tenagas')->onDelete('cascade');
            $table->foreign('materi_id')->references('materi_id')->on('materis')->onDelete('cascade');
        });
    }

    /**
     * Hapus tabel pivot.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_jenis_tenagas');
    }
};
