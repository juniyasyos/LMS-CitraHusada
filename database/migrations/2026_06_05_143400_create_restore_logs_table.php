<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restore_logs', function (Blueprint $table) {
            $table->id();
            $table->string('backup_file');
            $table->unsignedBigInteger('restored_by');
            $table->timestamp('restore_started_at')->nullable();
            $table->timestamp('restore_finished_at')->nullable();
            $table->enum('status', ['in_progress', 'success', 'failed', 'rolled_back'])->default('in_progress');
            $table->text('message')->nullable();
            $table->string('pre_restore_backup')->nullable();
            $table->timestamps();

            $table->foreign('restored_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restore_logs');
    }
};
