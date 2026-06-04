<?php

/**
 * Migration: Make file_materi nullable in sub_materis table.
 * Tujuan: Memungkinkan sub_materis disimpan tanpa file (untuk permanent delete).
 * Saat materi dihapus permanen, file fisik dihapus tapi record tetap ada dengan file_materi = null.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_materis', function (Blueprint $table) {
            $table->string('file_materi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sub_materis', function (Blueprint $table) {
            $table->string('file_materi')->nullable(false)->change();
        });
    }
};
