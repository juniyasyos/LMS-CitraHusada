<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkorUsersSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'skor_id' => 16,
    'progress_id' => 9,
    'post_test_id' => 1,
    'skor' => 63,
    'waktu_pengerjaan' => 84,
    'percobaan' => 2,
    'created_at' => '2026-04-07 03:22:04',
    'updated_at' => '2026-04-23 08:45:42',
  ),
  1 => 
  array (
    'skor_id' => 19,
    'progress_id' => 16,
    'post_test_id' => 2,
    'skor' => 100,
    'waktu_pengerjaan' => 4,
    'percobaan' => 2,
    'created_at' => '2026-04-10 06:15:43',
    'updated_at' => '2026-04-14 02:45:22',
  ),
  2 => 
  array (
    'skor_id' => 20,
    'progress_id' => 16,
    'post_test_id' => 3,
    'skor' => 100,
    'waktu_pengerjaan' => 13,
    'percobaan' => 2,
    'created_at' => '2026-04-10 06:17:47',
    'updated_at' => '2026-04-14 02:45:03',
  ),
  3 => 
  array (
    'skor_id' => 21,
    'progress_id' => 67,
    'post_test_id' => 2,
    'skor' => 100,
    'waktu_pengerjaan' => 12,
    'percobaan' => 1,
    'created_at' => '2026-04-27 07:25:42',
    'updated_at' => '2026-04-27 07:26:08',
  ),
  4 => 
  array (
    'skor_id' => 22,
    'progress_id' => 67,
    'post_test_id' => 3,
    'skor' => 0,
    'waktu_pengerjaan' => 7,
    'percobaan' => 1,
    'created_at' => '2026-04-27 07:26:26',
    'updated_at' => '2026-04-27 07:26:39',
  ),
  5 => 
  array (
    'skor_id' => 23,
    'progress_id' => 72,
    'post_test_id' => 2,
    'skor' => 0,
    'waktu_pengerjaan' => 11,
    'percobaan' => 0,
    'created_at' => '2026-04-28 02:44:49',
    'updated_at' => '2026-04-28 02:45:52',
  ),
  6 => 
  array (
    'skor_id' => 24,
    'progress_id' => 72,
    'post_test_id' => 3,
    'skor' => 0,
    'waktu_pengerjaan' => 7,
    'percobaan' => 0,
    'created_at' => '2026-04-28 02:45:39',
    'updated_at' => '2026-04-28 02:45:52',
  ),
  7 => 
  array (
    'skor_id' => 25,
    'progress_id' => 68,
    'post_test_id' => 1,
    'skor' => 25,
    'waktu_pengerjaan' => 30,
    'percobaan' => 4,
    'created_at' => '2026-05-06 13:23:04',
    'updated_at' => '2026-05-06 17:54:38',
  ),
);
        if(count($data) > 0) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('skor_users')->insert($data);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
