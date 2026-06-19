<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `backup_logs` MODIFY COLUMN `status` ENUM('success', 'failed', 'in_progress') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `backup_logs` MODIFY COLUMN `status` ENUM('success', 'failed') NOT NULL");
    }
};
