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
        // 1. Pindahkan data dari tabel users ke tabel pivot user_unit_kerja
        $users = DB::table('users')->whereNotNull('unit_kerja_id')->get();
        foreach ($users as $user) {
            DB::table('user_unit_kerja')->updateOrInsert(
                ['user_id' => $user->user_id, 'unit_kerja_id' => $user->unit_kerja_id]
            );
        }

        // 2. Hapus foreign key dan kolom unit_kerja_id di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_kerja_id']);
            $table->dropColumn('unit_kerja_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_kerja_id')->nullable();
            $table->foreign('unit_kerja_id')->references('unit_kerja_id')->on('unit_kerjas')->onDelete('set null');
        });

        // 3. Kembalikan data (mengambil 1 unit kerja saja secara random dari pivot karena belongsTo hanya bisa 1)
        $pivots = DB::table('user_unit_kerja')->get();
        foreach ($pivots as $pivot) {
            DB::table('users')->where('user_id', $pivot->user_id)->update(['unit_kerja_id' => $pivot->unit_kerja_id]);
        }
    }
};
