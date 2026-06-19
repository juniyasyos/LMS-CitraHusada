<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Direktur;

class DirekturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Direktur::create([
            'nama' => 'Nama Direktur',
            'jabatan' => 'Direktur Utama',
            'nik' => '123456789',
            'ttd_path' => 'Sertifikat/ttd/contoh-ttd.png',
        ]);
    }
}