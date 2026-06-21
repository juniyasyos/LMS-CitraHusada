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
        Schema::table('soals', function (Blueprint $table) {
            $table->text('pilihan_1')->change();
            $table->text('pilihan_2')->nullable()->change();
            $table->text('pilihan_3')->nullable()->change();
            $table->text('pilihan_4')->nullable()->change();
            $table->text('pilihan_5')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            $table->string('pilihan_1', 255)->change();
            $table->string('pilihan_2', 255)->nullable()->change();
            $table->string('pilihan_3', 255)->nullable()->change();
            $table->string('pilihan_4', 255)->nullable()->change();
            $table->string('pilihan_5', 255)->nullable()->change();
        });
    }
};
