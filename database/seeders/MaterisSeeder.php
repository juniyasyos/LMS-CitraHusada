<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterisSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'materi_id' => 1,
    'kategori_id' => 1,
    'judul' => 'Materi 1',
    'subjudul' => 'testing sub judul 1',
    'deskripsi' => 'Materi pelatihan mengenai standar postman.',
    'image_path' => 'materi/cover/Cover Materi 1.jpg',
    'arsip' => 0,
    'tanggal_upload' => '2026-03-02',
    'tanggal_selesai' => '2026-07-05',
    'jam_pelajaran' => 4,
    'created_at' => '2026-03-08 10:56:14',
    'updated_at' => '2026-05-06 16:37:48',
    'deleted_at' => NULL,
  ),
  1 => 
  array (
    'materi_id' => 2,
    'kategori_id' => 1,
    'judul' => 'materi 2',
    'subjudul' => 'test materi 2',
    'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
    'image_path' => 'materi/cover/materi 2.png',
    'arsip' => 0,
    'tanggal_upload' => '2026-03-08',
    'tanggal_selesai' => '2026-05-14',
    'jam_pelajaran' => 2,
    'created_at' => '2026-03-08 12:46:44',
    'updated_at' => '2026-05-03 14:03:20',
    'deleted_at' => NULL,
  ),
  2 => 
  array (
    'materi_id' => 3,
    'kategori_id' => 1,
    'judul' => 'materi 3',
    'subjudul' => 'subjudul 3',
    'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
    'image_path' => 'materi/cover/materi_3.png',
    'arsip' => 0,
    'tanggal_upload' => '2026-03-08',
    'tanggal_selesai' => '2026-05-10',
    'jam_pelajaran' => 2,
    'created_at' => '2026-03-08 13:15:50',
    'updated_at' => '2026-05-03 15:10:00',
    'deleted_at' => NULL,
  ),
  3 => 
  array (
    'materi_id' => 5,
    'kategori_id' => 1,
    'judul' => 'Upload Materi 001',
    'subjudul' => 'Testing',
    'deskripsi' => 'lorem ipasum',
    'image_path' => 'materi/Cover/kAF9HJroxOmLTnIHGhzF0OFDOEyNrsnE8NUNOPT8.jpg',
    'arsip' => 0,
    'tanggal_upload' => '2026-04-24',
    'tanggal_selesai' => '2026-05-01',
    'jam_pelajaran' => 10,
    'created_at' => '2026-04-23 05:07:24',
    'updated_at' => '2026-05-05 03:55:05',
    'deleted_at' => NULL,
  ),
  4 => 
  array (
    'materi_id' => 6,
    'kategori_id' => 1,
    'judul' => 'testing folder',
    'subjudul' => 'testtttt',
    'deskripsi' => 'lorem ipsum',
    'image_path' => NULL,
    'arsip' => 0,
    'tanggal_upload' => '2026-05-03',
    'tanggal_selesai' => '2026-05-10',
    'jam_pelajaran' => 3,
    'created_at' => '2026-05-03 14:04:06',
    'updated_at' => '2026-05-06 16:48:58',
    'deleted_at' => NULL,
  ),
  5 => 
  array (
    'materi_id' => 7,
    'kategori_id' => 1,
    'judul' => 'testing 3',
    'subjudul' => 'testing 3',
    'deskripsi' => 'testttt',
    'image_path' => NULL,
    'arsip' => 0,
    'tanggal_upload' => '2026-05-07',
    'tanggal_selesai' => '2026-05-08',
    'jam_pelajaran' => 3,
    'created_at' => '2026-05-03 14:44:31',
    'updated_at' => '2026-05-05 03:54:56',
    'deleted_at' => NULL,
  ),
  6 => 
  array (
    'materi_id' => 8,
    'kategori_id' => 3,
    'judul' => 'Testing upload',
    'subjudul' => 'testing sub judul 1',
    'deskripsi' => 'tes',
    'image_path' => 'materi/Cover/Xjfserk6isK1oAjr5E4cWYmac1WKsp7SK6ZqDUSK.png',
    'arsip' => 0,
    'tanggal_upload' => '2026-04-26',
    'tanggal_selesai' => '2026-05-04',
    'jam_pelajaran' => 3,
    'created_at' => '2026-05-03 14:46:15',
    'updated_at' => '2026-05-05 03:25:11',
    'deleted_at' => NULL,
  ),
  7 => 
  array (
    'materi_id' => 10,
    'kategori_id' => 1,
    'judul' => 'test',
    'subjudul' => 'test',
    'deskripsi' => 'test',
    'image_path' => 'materi/Cover/7OSCfzkg0Ecy1hMUsgUE3HV9ps9dqy5iPbUz9UmV.png',
    'arsip' => 1,
    'tanggal_upload' => '2026-04-26',
    'tanggal_selesai' => '2026-05-06',
    'jam_pelajaran' => 3,
    'created_at' => '2026-05-03 15:16:12',
    'updated_at' => '2026-05-05 03:55:01',
    'deleted_at' => NULL,
  ),
  8 => 
  array (
    'materi_id' => 11,
    'kategori_id' => 3,
    'judul' => 'testtttttttt',
    'subjudul' => 'test',
    'deskripsi' => NULL,
    'image_path' => NULL,
    'arsip' => 0,
    'tanggal_upload' => '2026-04-26',
    'tanggal_selesai' => '2026-04-30',
    'jam_pelajaran' => 3,
    'created_at' => '2026-05-03 15:17:18',
    'updated_at' => '2026-05-03 15:20:56',
    'deleted_at' => NULL,
  ),
  9 => 
  array (
    'materi_id' => 12,
    'kategori_id' => 1,
    'judul' => 'testing 3333',
    'subjudul' => 'yessss',
    'deskripsi' => 'blablabla',
    'image_path' => NULL,
    'arsip' => 0,
    'tanggal_upload' => '2026-04-26',
    'tanggal_selesai' => '2026-05-06',
    'jam_pelajaran' => 3,
    'created_at' => '2026-05-05 03:42:58',
    'updated_at' => '2026-05-05 03:42:58',
    'deleted_at' => NULL,
  ),
  10 => 
  array (
    'materi_id' => 14,
    'kategori_id' => 1,
    'judul' => 'Pelatihan postman_Updated',
    'subjudul' => 'Dasar-dasar postman',
    'deskripsi' => 'Materi pelatihan mengenai standar postman.',
    'image_path' => NULL,
    'arsip' => 0,
    'tanggal_upload' => '2026-03-01',
    'tanggal_selesai' => '2026-04-30',
    'jam_pelajaran' => 4,
    'created_at' => '2026-05-06 15:31:18',
    'updated_at' => '2026-05-06 16:44:26',
    'deleted_at' => NULL,
  ),
  11 => 
  array (
    'materi_id' => 15,
    'kategori_id' => 1,
    'judul' => 'Pelatihan postmannnnn_deleted',
    'subjudul' => 'Dasar-dasar postman',
    'deskripsi' => 'Materi pelatihan mengenai standar postman.',
    'image_path' => NULL,
    'arsip' => 0,
    'tanggal_upload' => '2026-05-01',
    'tanggal_selesai' => '2026-05-31',
    'jam_pelajaran' => 4,
    'created_at' => '2026-05-06 15:36:27',
    'updated_at' => '2026-05-06 16:49:15',
    'deleted_at' => '2026-05-06 16:49:15',
  ),
);
        if(count($data) > 0) {
            DB::table('materis')->insert($data);
        }
    }
}
