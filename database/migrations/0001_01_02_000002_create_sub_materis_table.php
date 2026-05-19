<?php

/**
 * Tabel sub_materis (consolidated).
 * Added: deskripsi column (used by controller & model).
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_materis', function (Blueprint $table) {
            $table->bigIncrements('sub_materi_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_materi');
            $table->unsignedInteger('urutan_sub_materi');
            $table->timestamps();

            $table->foreign('materi_id')->references('materi_id')->on('materis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_materis');
    }
};
