<?php

/**
 * Migrasi: membuat tabel `post_tests`.
 *
 * Berisi soal ujian akhir (post-test) yang terkait dengan sub-materi.
 * Menyimpan pertanyaan, lima pilihan, jawaban benar, serta flag
 * status pilihan apakah menggunakan opsi berganda atau bukan.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel `post_tests` beserta foreign key ke `sub_materis`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('post_tests', function (Blueprint $table) {
            $table->bigIncrements('post_test_id');
            $table->unsignedBigInteger('materi_id');
            $table->unsignedBigInteger('urutan_post_test');
            $table->unsignedInteger('waktu_pengerjaan');// dalam menit
            $table->timestamps();

            $table->foreign('materi_id')->references('materi_id')->on('materis')->onDelete('cascade');
        });
    }

    /**
     * Hapus tabel `post_tests`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tests');
    }
};
