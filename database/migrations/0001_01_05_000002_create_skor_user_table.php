<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skor_user', function (Blueprint $table) {
            $table->bigIncrements('skor_id');
            $table->unsignedBigInteger('progress_id');
            $table->unsignedBigInteger('post_test_id');
            $table->unsignedInteger('skor');
            $table->dateTime('waktu_mulai_pengerjaan')->nullable();
            $table->dateTime('waktu_selesai_pengerjaan')->nullable();
            $table->timestamps();

            $table->foreign('progress_id')->references('progress_id')->on('user_progress')->onDelete('cascade');
            $table->foreign('post_test_id')->references('post_test_id')->on('post_tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skor_user');
    }
};
