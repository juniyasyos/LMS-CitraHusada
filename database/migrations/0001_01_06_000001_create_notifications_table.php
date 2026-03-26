<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Relasi ke user
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Tipe notifikasi
            $table->string('type'); 
            // contoh: materi_baru, deadline, sertifikat

            // Judul & pesan
            $table->string('title');
            $table->text('message');

            // Status dibaca
            $table->boolean('is_read')->default(false);

            // Data tambahan (opsional)
            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
