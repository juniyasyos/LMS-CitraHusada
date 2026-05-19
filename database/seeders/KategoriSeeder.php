<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Umum', 'keterangan' => 'Pelatihan untuk seluruh staf rumah sakit'],
            ['nama_kategori' => 'Medis', 'keterangan' => 'Pelatihan khusus tenaga medis dan keperawatan'],
            ['nama_kategori' => 'Manajemen', 'keterangan' => 'Pelatihan manajerial dan kepemimpinan'],
            ['nama_kategori' => 'IT & Sistem', 'keterangan' => 'Pelatihan penggunaan SIMRS dan teknologi'],
        ];

        foreach ($kategoris as $kategori) {
            \App\Models\Kategori::updateOrCreate(
                ['nama_kategori' => $kategori['nama_kategori']],
                ['keterangan' => $kategori['keterangan']]
            );
        }
    }
}
