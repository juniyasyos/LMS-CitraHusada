<?php

/**
 * Migrasi: membuat tabel `users`.
 *
 * Berkas ini mendefinisikan struktur tabel pengguna utama aplikasi. Kolom-
 * kolom yang dibuat mencakup nama, NIK unik, kata sandi, serta relasi ke
 * jenis tenaga dan unit kerja. Kolom `role_id` menentukan peran pengguna
 * (default 43) dan `status` menandakan apakah akun aktif.
 *
 * Catatan:
 *  - Kunci asing (foreign key) ditambahkan pada migrasi lain agar
 *    memastikan tabel referensi sudah ada terlebih dahulu.
 *  - Timestamps Laravel (`created_at` dan `updated_at`) otomatis ditangani.
 *
 * Fungsi `up()` menjalankan migrasi, sementara `down()` menghapus tabel saat
 * rollback.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi: membuat tabel `users`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('nama');
            $table->unsignedBigInteger('jenis_tenaga_id')->nullable();
            $table->unsignedBigInteger('unit_kerja_id')->nullable();
            $table->string('nik')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id')->default(43);
            $table->boolean('status')->default(true);
            $table->timestamps();

            // foreign keys will be added in a separate migration to ensure referenced tables exist
        });
    }

    /**
     * Menghapus tabel `users` saat rollback migrasi.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
