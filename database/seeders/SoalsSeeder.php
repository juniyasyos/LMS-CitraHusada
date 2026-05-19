<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoalsSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'soal_id' => 1,
    'post_test_id' => 1,
    'urutan_soal' => 1,
    'status_pilihan' => 0,
    'soal' => 'Apakah benar ?',
    'pilihan_1' => 'Benar',
    'pilihan_2' => 'Tidak benar',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '2',
    'poin' => 10,
    'created_at' => '2026-03-12 02:15:06',
    'updated_at' => '2026-03-12 02:15:06',
  ),
  1 => 
  array (
    'soal_id' => 2,
    'post_test_id' => 1,
    'urutan_soal' => 2,
    'status_pilihan' => 1,
    'soal' => 'Apa yang dimaksud dengan keselamatan pasien di rumah sakit?',
    'pilihan_1' => 'Kegiatan promosi kesehatan kepada masyarakat',
    'pilihan_2' => 'Upaya meningkatkan jumlah pasien yang dirawat',
    'pilihan_3' => '
Sistem untuk membuat pelayanan pasien lebih aman',
    'pilihan_4' => '
Proses administrasi rumah sakit',
    'pilihan_5' => 'tidak ada',
    'jawaban_benar' => '2,4',
    'poin' => 10,
    'created_at' => '2026-03-12 02:15:06',
    'updated_at' => '2026-03-12 02:15:06',
  ),
  2 => 
  array (
    'soal_id' => 3,
    'post_test_id' => 1,
    'urutan_soal' => 3,
    'status_pilihan' => 0,
    'soal' => 'Apa yang dimaksud dengan keselamatan pasien di rumah sakit?',
    'pilihan_1' => 'Kegiatan promosi kesehatan kepada masyarakat',
    'pilihan_2' => 'Upaya meningkatkan jumlah pasien yang dirawat',
    'pilihan_3' => '
Sistem untuk membuat pelayanan pasien lebih aman',
    'pilihan_4' => '
Proses administrasi rumah sakit',
    'pilihan_5' => '',
    'jawaban_benar' => '1',
    'poin' => 10,
    'created_at' => '2026-03-12 02:15:06',
    'updated_at' => '2026-03-12 02:15:06',
  ),
  3 => 
  array (
    'soal_id' => 4,
    'post_test_id' => 2,
    'urutan_soal' => 1,
    'status_pilihan' => 0,
    'soal' => 'Benar',
    'pilihan_1' => 'Tidak',
    'pilihan_2' => 'Iya',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '2',
    'poin' => NULL,
    'created_at' => '2026-04-03 16:56:18',
    'updated_at' => '2026-04-03 16:56:18',
  ),
  4 => 
  array (
    'soal_id' => 5,
    'post_test_id' => 2,
    'urutan_soal' => 2,
    'status_pilihan' => 0,
    'soal' => 'Tidak',
    'pilihan_1' => 'Tidak',
    'pilihan_2' => 'Iya',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1',
    'poin' => NULL,
    'created_at' => '2026-04-03 16:58:38',
    'updated_at' => '2026-04-03 16:58:38',
  ),
  5 => 
  array (
    'soal_id' => 6,
    'post_test_id' => 3,
    'urutan_soal' => 1,
    'status_pilihan' => 0,
    'soal' => 'Apa iya ?',
    'pilihan_1' => 'Ya',
    'pilihan_2' => 'Tidak',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1',
    'poin' => NULL,
    'created_at' => '2026-04-03 17:00:10',
    'updated_at' => '2026-04-03 17:00:10',
  ),
  6 => 
  array (
    'soal_id' => 7,
    'post_test_id' => 3,
    'urutan_soal' => 2,
    'status_pilihan' => 0,
    'soal' => 'Jawab Tidak',
    'pilihan_1' => 'Ya',
    'pilihan_2' => 'Tidak',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '2',
    'poin' => NULL,
    'created_at' => '2026-04-03 17:00:10',
    'updated_at' => '2026-04-03 17:00:10',
  ),
  7 => 
  array (
    'soal_id' => 8,
    'post_test_id' => 1,
    'urutan_soal' => 4,
    'status_pilihan' => 1,
    'soal' => 'Iya',
    'pilihan_1' => 'Iya',
    'pilihan_2' => 'Tidak',
    'pilihan_3' => 'Iya',
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1,3',
    'poin' => NULL,
    'created_at' => '2026-04-07 10:18:38',
    'updated_at' => '2026-04-07 10:18:38',
  ),
  8 => 
  array (
    'soal_id' => 9,
    'post_test_id' => 1,
    'urutan_soal' => 5,
    'status_pilihan' => 0,
    'soal' => 'Iya',
    'pilihan_1' => 'Iya',
    'pilihan_2' => 'Tidak',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1',
    'poin' => NULL,
    'created_at' => '2026-04-07 10:20:17',
    'updated_at' => '2026-04-07 10:20:17',
  ),
  9 => 
  array (
    'soal_id' => 10,
    'post_test_id' => 1,
    'urutan_soal' => 6,
    'status_pilihan' => 0,
    'soal' => 'Iya',
    'pilihan_1' => 'Iya',
    'pilihan_2' => 'Tidak',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1',
    'poin' => NULL,
    'created_at' => '2026-04-07 10:20:17',
    'updated_at' => '2026-04-07 10:20:17',
  ),
  10 => 
  array (
    'soal_id' => 11,
    'post_test_id' => 1,
    'urutan_soal' => 7,
    'status_pilihan' => 0,
    'soal' => 'Iya',
    'pilihan_1' => 'Iya',
    'pilihan_2' => 'Tidak',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1',
    'poin' => NULL,
    'created_at' => '2026-04-07 10:20:17',
    'updated_at' => '2026-04-07 10:20:17',
  ),
  11 => 
  array (
    'soal_id' => 12,
    'post_test_id' => 1,
    'urutan_soal' => 8,
    'status_pilihan' => 0,
    'soal' => 'Iya',
    'pilihan_1' => 'Iya',
    'pilihan_2' => 'Tidak',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1',
    'poin' => NULL,
    'created_at' => '2026-04-07 10:20:17',
    'updated_at' => '2026-04-07 10:20:17',
  ),
  12 => 
  array (
    'soal_id' => 13,
    'post_test_id' => 5,
    'urutan_soal' => 1,
    'status_pilihan' => 1,
    'soal' => 'testing 1
A dan C',
    'pilihan_1' => '1',
    'pilihan_2' => '2',
    'pilihan_3' => '3',
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1,3',
    'poin' => 10,
    'created_at' => '2026-04-23 14:08:50',
    'updated_at' => '2026-04-24 16:20:59',
  ),
  13 => 
  array (
    'soal_id' => 14,
    'post_test_id' => 5,
    'urutan_soal' => 2,
    'status_pilihan' => 0,
    'soal' => 'testing 2
1',
    'pilihan_1' => '1',
    'pilihan_2' => '2',
    'pilihan_3' => NULL,
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1',
    'poin' => 10,
    'created_at' => '2026-04-23 14:08:50',
    'updated_at' => '2026-04-24 16:20:59',
  ),
  14 => 
  array (
    'soal_id' => 15,
    'post_test_id' => 6,
    'urutan_soal' => 1,
    'status_pilihan' => 1,
    'soal' => 'testing',
    'pilihan_1' => '1',
    'pilihan_2' => '2',
    'pilihan_3' => '3',
    'pilihan_4' => NULL,
    'pilihan_5' => NULL,
    'jawaban_benar' => '1,3',
    'poin' => 10,
    'created_at' => '2026-04-24 16:25:29',
    'updated_at' => '2026-04-24 16:25:29',
  ),
  15 => 
  array (
    'soal_id' => 21,
    'post_test_id' => 8,
    'urutan_soal' => 1,
    'status_pilihan' => 0,
    'soal' => 'Apa kepanjangan dari BHD?',
    'pilihan_1' => 'Bantuan Hidup Dasar',
    'pilihan_2' => 'Bantuan Hari Depan',
    'pilihan_3' => 'Bantuan Hidup Darurat',
    'pilihan_4' => 'Bantuan Hati Damai',
    'pilihan_5' => 'Bukan Harapan Dusta',
    'jawaban_benar' => '1',
    'poin' => 10,
    'created_at' => '2026-05-06 17:36:14',
    'updated_at' => '2026-05-06 17:36:14',
  ),
  16 => 
  array (
    'soal_id' => 22,
    'post_test_id' => 8,
    'urutan_soal' => 2,
    'status_pilihan' => 0,
    'soal' => 'Berapa perbandingan kompresi dan ventilasi pada orang dewasa?',
    'pilihan_1' => '15:2',
    'pilihan_2' => '30:2',
    'pilihan_3' => '10:1',
    'pilihan_4' => '5:1',
    'pilihan_5' => 'Semua Salah',
    'jawaban_benar' => '2',
    'poin' => 10,
    'created_at' => '2026-05-06 17:36:14',
    'updated_at' => '2026-05-06 17:36:14',
  ),
);
        if(count($data) > 0) {
            // Fix: replace NULL poin with default value 0
            $data = array_map(function ($row) {
                $row['poin'] = $row['poin'] ?? 0;
                return $row;
            }, $data);
            DB::table('soals')->insert($data);
        }
    }
}
