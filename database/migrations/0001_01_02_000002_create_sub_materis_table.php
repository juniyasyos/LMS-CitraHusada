<?php

/**
 * Migrasi: membuat tabel `sub_materis`.
 *
 * Setiap sub-materi terhubung ke materi induk. Menyimpan judul dan
 * file opsional dari sub-materi. Foreign key dengan cascading delete
 * memastikan sub-materi dihapus ketika materi induk dihapus.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel `sub_materis`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sub_materis', function (Blueprint $table) {
            $table->bigIncrements('sub_materi_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('judul');
            $table->string('file_materi');
            $table->unsignedInteger('urutan_sub_materi');
            $table->timestamps();

            $table->foreign('materi_id')->references('materi_id')->on('materis')->onDelete('cascade');
        });
    }

    /**
     * Hapus tabel `sub_materis`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_materis');
    }
};
