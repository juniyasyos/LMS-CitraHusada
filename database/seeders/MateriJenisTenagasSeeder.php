<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriJenisTenagasSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'materi_jenis_tenaga_id' => 1,
    'jenis_tenaga_id' => 1,
    'materi_id' => 1,
    'created_at' => '2026-03-08 11:10:25',
    'updated_at' => '2026-03-08 11:10:25',
  ),
  1 => 
  array (
    'materi_jenis_tenaga_id' => 2,
    'jenis_tenaga_id' => 1,
    'materi_id' => 3,
    'created_at' => '2026-03-08 13:17:01',
    'updated_at' => '2026-03-08 13:17:01',
  ),
  2 => 
  array (
    'materi_jenis_tenaga_id' => 3,
    'jenis_tenaga_id' => 18,
    'materi_id' => 5,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  3 => 
  array (
    'materi_jenis_tenaga_id' => 4,
    'jenis_tenaga_id' => 2,
    'materi_id' => 6,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  4 => 
  array (
    'materi_jenis_tenaga_id' => 5,
    'jenis_tenaga_id' => 2,
    'materi_id' => 8,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  5 => 
  array (
    'materi_jenis_tenaga_id' => 6,
    'jenis_tenaga_id' => 9,
    'materi_id' => 8,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  6 => 
  array (
    'materi_jenis_tenaga_id' => 7,
    'jenis_tenaga_id' => 5,
    'materi_id' => 10,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  7 => 
  array (
    'materi_jenis_tenaga_id' => 8,
    'jenis_tenaga_id' => 1,
    'materi_id' => 14,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  8 => 
  array (
    'materi_jenis_tenaga_id' => 9,
    'jenis_tenaga_id' => 4,
    'materi_id' => 14,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  9 => 
  array (
    'materi_jenis_tenaga_id' => 10,
    'jenis_tenaga_id' => 1,
    'materi_id' => 15,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  10 => 
  array (
    'materi_jenis_tenaga_id' => 11,
    'jenis_tenaga_id' => 4,
    'materi_id' => 15,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  11 => 
  array (
    'materi_jenis_tenaga_id' => 14,
    'jenis_tenaga_id' => 4,
    'materi_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
);
        if(count($data) > 0) {
            DB::table('materi_jenis_tenagas')->insert($data);
        }
    }
}
