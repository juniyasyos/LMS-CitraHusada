<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('roles')->nullable()->after('password');
        });

        // Migrate data manually
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $roles = [];
            if ($user->role_id == 1) $roles = ['superadmin'];
            elseif ($user->role_id == 2) $roles = ['admin'];
            elseif ($user->role_id == 3) $roles = ['teacher'];
            elseif ($user->role_id == 4) $roles = ['karyawan'];

            if (!empty($roles)) {
                DB::table('users')->where('user_id', $user->user_id)->update(['roles' => json_encode($roles)]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('password');
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('set null');
        });

        // Restore data
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $roles = json_decode($user->roles, true) ?? [];
            $role_id = null;
            if (in_array('superadmin', $roles)) $role_id = 1;
            elseif (in_array('admin', $roles)) $role_id = 2;
            elseif (in_array('teacher', $roles)) $role_id = 3;
            elseif (in_array('karyawan', $roles)) $role_id = 4;

            if ($role_id) {
                DB::table('users')->where('user_id', $user->user_id)->update(['role_id' => $role_id]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
};
