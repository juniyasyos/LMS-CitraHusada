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
        // Sengaja dikosongkan untuk me-replace/meng-override migrasi unit_kerja dari nexaid-client.
        // Aplikasi LMS tidak memerlukan tabel ini karena sudah menggunakan tabel "unit_kerjas" bawaan.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kosong
    }
};
