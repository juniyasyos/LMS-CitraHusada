<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_tests', function (Blueprint $table) {
            $table->boolean('pretest')->default(false)->after('ulang_post_test');
        });
    }

    public function down(): void
    {
        Schema::table('post_tests', function (Blueprint $table) {
            $table->dropColumn('pretest');
        });
    }
};
