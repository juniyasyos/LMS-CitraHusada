<?php

/**
 * Tabel post_tests (consolidated).
 * Merged from: create_post_tests + add_judul_and_attempts.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_tests', function (Blueprint $table) {
            $table->bigIncrements('post_test_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('judul')->nullable();
            $table->unsignedBigInteger('urutan_post_test');
            $table->unsignedInteger('waktu_pengerjaan'); // dalam menit
            $table->unsignedInteger('ulang_post_test')->default(1);
            $table->timestamps();

            $table->foreign('materi_id')->references('materi_id')->on('materis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_tests');
    }
};
