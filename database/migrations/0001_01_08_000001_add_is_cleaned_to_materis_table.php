<?php

/**
 * Migration: Add is_cleaned column to materis table.
 * Tujuan: Menandai materi yang filenya sudah dibersihkan (hapus permanen).
 * - is_cleaned = false: File masih ada di storage (normal)
 * - is_cleaned = true: File sudah dihapus dari storage, data DB dipertahankan untuk riwayat
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            // Default false (berarti file masih ada/belum dibersihkan)
            $table->boolean('is_cleaned')->default(false)->after('arsip');
        });
    }

    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            $table->dropColumn('is_cleaned');
        });
    }
};
