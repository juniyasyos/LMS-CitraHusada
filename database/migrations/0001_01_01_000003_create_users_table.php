<?php

/**
 * Tabel users (consolidated).
 * Merged from: create_users, add_foreign_keys, add_email_modify_status,
 *              add_remember_token, add_iam_id_change_status.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('iam_id')->nullable();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('jenis_tenaga_id')->nullable();
            $table->unsignedBigInteger('unit_kerja_id')->nullable();
            $table->string('nik')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedBigInteger('role_id')->default(4);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->integer('total_jpl')->default(0);
            $table->timestamps();

            $table->foreign('jenis_tenaga_id')->references('jenis_tenaga_id')->on('jenis_tenagas')->onDelete('set null');
            $table->foreign('unit_kerja_id')->references('unit_kerja_id')->on('unit_kerjas')->onDelete('set null');
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
