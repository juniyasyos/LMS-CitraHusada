<?php

/**
 * Tabel user_progress (consolidated).
 * Merged from: create_user_progress + alter_status_enum (added 'Sesi Berakhir').
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->bigIncrements('progress_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('materi_id');
            $table->unsignedInteger('urutan_selesai')->default(0);
            $table->unsignedInteger('skor_total')->default(0);

            $table->enum('status', [
                'Belum Dimulai',
                'Progres',
                'Selesai',
                'Gagal',
                'Sesi Berakhir'
            ])->default('Belum Dimulai');

            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('materi_id')->references('materi_id')->on('materis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
