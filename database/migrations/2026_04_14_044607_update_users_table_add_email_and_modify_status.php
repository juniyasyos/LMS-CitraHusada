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
        // Add email if not exists
        if (!Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('email')->nullable()->after('nama');
            });
            
            \Illuminate\Support\Facades\DB::table('users')->whereNull('email')->update([
                'email' => \Illuminate\Support\Facades\DB::raw("CONCAT('user', user_id, '@example.com')")
            ]);
        }

        // To safely modify status from boolean to Enum
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('status', 'old_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Ditangguhkan'])->default('Aktif')->after('role_id');
        });

        \Illuminate\Support\Facades\DB::table('users')->update([
            'status' => \Illuminate\Support\Facades\DB::raw("CASE WHEN old_status = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END")
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('old_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('status', 'new_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('status')->default(true);
        });

        \Illuminate\Support\Facades\DB::table('users')->update([
            'status' => \Illuminate\Support\Facades\DB::raw("CASE WHEN new_status = 'Aktif' THEN 1 ELSE 0 END")
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('new_status');
            $table->dropColumn('email');
        });
    }
};
