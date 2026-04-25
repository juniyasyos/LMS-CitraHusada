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
        Schema::table('post_tests', function (Blueprint $table) {
            if (!Schema::hasColumn('post_tests', 'judul')) {
                $table->string('judul')->after('materi_id')->nullable();
            }
            if (!Schema::hasColumn('post_tests', 'ulang_post_test')) {
                $table->unsignedInteger('ulang_post_test')->after('waktu_pengerjaan')->default(1);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_tests', function (Blueprint $table) {
            $table->dropColumn(['judul', 'ulang_post_test']);
        });
    }
};
