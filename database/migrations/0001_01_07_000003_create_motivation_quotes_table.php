<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motivation_quotes', function (Blueprint $table) {
            $table->id('quote_id');
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('kondisi', ['awal kuis', 'kelipatan 3', 'akhir kuis']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motivation_quotes');
    }
};
