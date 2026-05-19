<?php

/**
 * Tabel materis (consolidated).
 * Merged from: create_materis, add_kategori_id, add_soft_deletes, add_arsip.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->bigIncrements('materi_id');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->string('judul', 255);
            $table->string('subjudul', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('arsip')->default(false);
            $table->dateTime('tanggal_upload');
            $table->dateTime('tanggal_selesai');
            $table->unsignedInteger('jam_pelajaran');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kategori_id')->references('kategori_id')->on('kategoris')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
