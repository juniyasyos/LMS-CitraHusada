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
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->renameColumn('image', 'image_path');
        });

        Schema::table('sertifikats', function (Blueprint $table) {
            $table->string('image_path')->nullable()->change();
        });

        Schema::table('sertifikat_eksternals', function (Blueprint $table) {
            $table->string('image_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sertifikat_eksternals', function (Blueprint $table) {
            $table->string('image_path')->nullable(false)->change();
        });

        Schema::table('sertifikats', function (Blueprint $table) {
            $table->string('image_path')->nullable(false)->change();
        });

        Schema::table('sertifikats', function (Blueprint $table) {
            $table->renameColumn('image_path', 'image');
        });
    }
};
