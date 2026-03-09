<?php

/**
 * Migrasi: membuat tabel `sertifikats`.
 *
 * Menyimpan data sertifikat, termasuk
 * status persetujuan (enum: belum, disetujui, tidak disetujui). Relasi
 * ke tabel `users` dengan cascade delete sehingga sertifikat akan
 * hilang jika pengguna dihapus.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel sertifikat.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->bigIncrements('sertifikat_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('image');
            $table->enum('status', ['Belum Disetujui', 'Disetujui', 'Tidak Disetujui'])->default('Belum Disetujui');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('materi_id')->references('materi_id')->on('materis')->onDelete('cascade');
        });
    }

    /**
     * Hapus tabel `sertifikats`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
