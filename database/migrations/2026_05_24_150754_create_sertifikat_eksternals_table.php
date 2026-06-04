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
        Schema::create('sertifikat_eksternals', function (Blueprint $table) {
            $table->id('sertifikat_eksternal_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->string('judul', 255);
            $table->string('image_path');
            $table->integer('jpl')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['Belum Disetujui', 'Disetujui', 'Tidak Disetujui'])->default('Belum Disetujui');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_eksternals');
    }
};
