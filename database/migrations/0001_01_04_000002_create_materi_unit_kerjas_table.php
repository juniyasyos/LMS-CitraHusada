<?php

/**
 * Migrasi: membuat tabel pivot `materi_unit_kerjas`.
 *
 * Menghubungkan materi dengan unit kerja yang berhak mengaksesnya.
 * Dua foreign key berperilaku cascade delete.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel pivot untuk relasi materi <-> unit kerja.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('materi_unit_kerjas', function (Blueprint $table) {
            $table->bigIncrements('materi_unit_kerja_id');
            $table->unsignedBigInteger('materi_id');
            $table->unsignedBigInteger('unit_kerja_id');
            $table->timestamps();

            $table->foreign('materi_id')->references('materi_id')->on('materis')->onDelete('cascade');
            $table->foreign('unit_kerja_id')->references('unit_kerja_id')->on('unit_kerjas')->onDelete('cascade');
        });
    }

    /**
     * Hapus tabel pivot saat rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_unit_kerjas');
    }
};
