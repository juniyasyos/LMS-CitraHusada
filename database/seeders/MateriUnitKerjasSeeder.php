<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriUnitKerjasSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'materi_unit_kerja_id' => 1,
    'materi_id' => 2,
    'unit_kerja_id' => 1,
    'created_at' => '2026-03-08 12:47:24',
    'updated_at' => '2026-03-08 12:47:24',
  ),
  1 => 
  array (
    'materi_unit_kerja_id' => 3,
    'materi_id' => 5,
    'unit_kerja_id' => 40,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  2 => 
  array (
    'materi_unit_kerja_id' => 4,
    'materi_id' => 2,
    'unit_kerja_id' => 40,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  3 => 
  array (
    'materi_unit_kerja_id' => 6,
    'materi_id' => 5,
    'unit_kerja_id' => 40,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  4 => 
  array (
    'materi_unit_kerja_id' => 7,
    'materi_id' => 1,
    'unit_kerja_id' => 40,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  5 => 
  array (
    'materi_unit_kerja_id' => 8,
    'materi_id' => 6,
    'unit_kerja_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  6 => 
  array (
    'materi_unit_kerja_id' => 9,
    'materi_id' => 7,
    'unit_kerja_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  7 => 
  array (
    'materi_unit_kerja_id' => 10,
    'materi_id' => 8,
    'unit_kerja_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  8 => 
  array (
    'materi_unit_kerja_id' => 11,
    'materi_id' => 8,
    'unit_kerja_id' => 20,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  9 => 
  array (
    'materi_unit_kerja_id' => 12,
    'materi_id' => 10,
    'unit_kerja_id' => 3,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  10 => 
  array (
    'materi_unit_kerja_id' => 13,
    'materi_id' => 11,
    'unit_kerja_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  11 => 
  array (
    'materi_unit_kerja_id' => 14,
    'materi_id' => 14,
    'unit_kerja_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  12 => 
  array (
    'materi_unit_kerja_id' => 15,
    'materi_id' => 14,
    'unit_kerja_id' => 2,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  13 => 
  array (
    'materi_unit_kerja_id' => 16,
    'materi_id' => 14,
    'unit_kerja_id' => 3,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  14 => 
  array (
    'materi_unit_kerja_id' => 17,
    'materi_id' => 15,
    'unit_kerja_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  15 => 
  array (
    'materi_unit_kerja_id' => 18,
    'materi_id' => 15,
    'unit_kerja_id' => 2,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  16 => 
  array (
    'materi_unit_kerja_id' => 19,
    'materi_id' => 15,
    'unit_kerja_id' => 3,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  17 => 
  array (
    'materi_unit_kerja_id' => 23,
    'materi_id' => 1,
    'unit_kerja_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  18 => 
  array (
    'materi_unit_kerja_id' => 24,
    'materi_id' => 1,
    'unit_kerja_id' => 2,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  19 => 
  array (
    'materi_unit_kerja_id' => 25,
    'materi_id' => 1,
    'unit_kerja_id' => 3,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
);
        if(count($data) > 0) {
            DB::table('materi_unit_kerjas')->insert($data);
        }
    }
}
