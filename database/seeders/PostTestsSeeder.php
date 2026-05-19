<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostTestsSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'post_test_id' => 1,
    'materi_id' => 1,
    'judul' => NULL,
    'urutan_post_test' => 4,
    'waktu_pengerjaan' => 600,
    'ulang_post_test' => 50,
    'created_at' => '2026-03-08 11:03:50',
    'updated_at' => '2026-03-08 11:03:50',
  ),
  1 => 
  array (
    'post_test_id' => 2,
    'materi_id' => 2,
    'judul' => NULL,
    'urutan_post_test' => 3,
    'waktu_pengerjaan' => 60,
    'ulang_post_test' => 1,
    'created_at' => '2026-03-10 13:35:36',
    'updated_at' => '2026-03-10 13:35:36',
  ),
  2 => 
  array (
    'post_test_id' => 3,
    'materi_id' => 2,
    'judul' => NULL,
    'urutan_post_test' => 6,
    'waktu_pengerjaan' => 60,
    'ulang_post_test' => 1,
    'created_at' => '2026-04-03 16:54:48',
    'updated_at' => '2026-04-03 16:54:48',
  ),
  3 => 
  array (
    'post_test_id' => 5,
    'materi_id' => 5,
    'judul' => 'Testing kuis',
    'urutan_post_test' => 3,
    'waktu_pengerjaan' => 10,
    'ulang_post_test' => 3,
    'created_at' => '2026-04-23 14:08:50',
    'updated_at' => '2026-04-23 14:08:50',
  ),
  4 => 
  array (
    'post_test_id' => 6,
    'materi_id' => 3,
    'judul' => 'testing kuis',
    'urutan_post_test' => 2,
    'waktu_pengerjaan' => 10,
    'ulang_post_test' => 10,
    'created_at' => '2026-04-24 16:25:29',
    'updated_at' => '2026-04-24 16:25:29',
  ),
  5 => 
  array (
    'post_test_id' => 8,
    'materi_id' => 1,
    'judul' => 'Kuis Post-Test Dasar Postman_Update',
    'urutan_post_test' => 7,
    'waktu_pengerjaan' => 300,
    'ulang_post_test' => 4,
    'created_at' => '2026-05-06 17:31:13',
    'updated_at' => '2026-05-06 17:36:14',
  ),
);
        if(count($data) > 0) {
            DB::table('post_tests')->insert($data);
        }
    }
}
