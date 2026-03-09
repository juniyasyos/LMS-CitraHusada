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
        Schema::create('soals', function (Blueprint $table) {
            $table->bigIncrements('soal_id');
            $table->unsignedBigInteger('post_test_id');
            $table->unsignedBigInteger('urutan_soal');
            $table->boolean('status_pilihan')->default(false);
            $table->text('soal');
            $table->string('pilihan_1');
            $table->string('pilihan_2');
            $table->string('pilihan_3');
            $table->string('pilihan_4');
            $table->string('pilihan_5');
            $table->string('jawaban_benar');
            $table->timestamps();

            $table->foreign('post_test_id')->references('post_test_id')->on('post_tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};
