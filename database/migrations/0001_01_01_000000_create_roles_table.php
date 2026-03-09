<?php

/**
 * Migrasi: membuat tabel `roles`.
 *
 * Tabel ini menyimpan daftar peran yang dapat di-assign ke pengguna.
 * Kolom `role` menampung nama peran, dan timestamps otomatis mencatat
 * waktu pembuatan dan pembaruan.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (membuat tabel `roles`).
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('role_id');
            $table->string('role');
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi (menghapus tabel `roles`).
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
