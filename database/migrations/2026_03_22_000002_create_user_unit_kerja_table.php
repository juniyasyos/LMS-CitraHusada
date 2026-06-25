<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_unit_kerja')) {
            Schema::create('user_unit_kerja', function (Blueprint $table) {
                // We use user_id explicitly because the users table PK is user_id, not id.
                // This prevents the vendor migration from failing due to missing 'id' column on users table.
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
                
                $table->unsignedBigInteger('unit_kerja_id');
                $table->foreign('unit_kerja_id')->references('unit_kerja_id')->on('unit_kerjas')->cascadeOnDelete()->cascadeOnUpdate();
                $table->primary(['user_id', 'unit_kerja_id']);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_unit_kerja');
    }
};
