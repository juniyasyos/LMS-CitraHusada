<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubMaterisSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'sub_materi_id' => 1,
    'materi_id' => 1,
    'judul' => 'sub judul 1',
    'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."',
    'file_materi' => 'materi/PDF/materi1.pdf',
    'urutan_sub_materi' => 1,
    'created_at' => '2026-03-08 11:01:23',
    'updated_at' => '2026-03-08 11:01:23',
  ),
  1 => 
  array (
    'sub_materi_id' => 2,
    'materi_id' => 1,
    'judul' => 'sub judul 2 (update)',
    'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."',
    'file_materi' => 'materi/PDF/materi2.pdf',
    'urutan_sub_materi' => 2,
    'created_at' => '2026-03-08 11:01:23',
    'updated_at' => '2026-03-08 11:01:23',
  ),
  2 => 
  array (
    'sub_materi_id' => 3,
    'materi_id' => 1,
    'judul' => 'sub judul 3',
    'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."',
    'file_materi' => 'materi/Video/contoh.mp4',
    'urutan_sub_materi' => 3,
    'created_at' => '2026-03-08 11:01:23',
    'updated_at' => '2026-03-08 11:01:23',
  ),
  3 => 
  array (
    'sub_materi_id' => 4,
    'materi_id' => 2,
    'judul' => 'sub materi 2',
    'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."',
    'file_materi' => 'materi/PDF/materi1.pdf',
    'urutan_sub_materi' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  4 => 
  array (
    'sub_materi_id' => 5,
    'materi_id' => 3,
    'judul' => 'sub materi 3',
    'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."',
    'file_materi' => 'materi/PDF/materi1.pdf',
    'urutan_sub_materi' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  5 => 
  array (
    'sub_materi_id' => 6,
    'materi_id' => 2,
    'judul' => 'sub materi 2.1',
    'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."',
    'file_materi' => 'materi/PDF/materi1.pdf',
    'urutan_sub_materi' => 2,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
  6 => 
  array (
    'sub_materi_id' => 7,
    'materi_id' => 2,
    'judul' => 'sub materi 2.4',
    'deskripsi' => 'lorem ipsum',
    'file_materi' => 'materi/PDF/materi1.pdf',
    'urutan_sub_materi' => 4,
    'created_at' => '2026-04-03 16:52:37',
    'updated_at' => '2026-04-03 16:52:37',
  ),
  7 => 
  array (
    'sub_materi_id' => 8,
    'materi_id' => 2,
    'judul' => 'sub materi 5',
    'deskripsi' => 'blablabla',
    'file_materi' => 'materi/PPT/KOMPRESI_DATA_MULTIMEDIA_pptx.pptx',
    'urutan_sub_materi' => 5,
    'created_at' => '2026-04-03 16:53:58',
    'updated_at' => '2026-04-03 16:53:58',
  ),
  8 => 
  array (
    'sub_materi_id' => 9,
    'materi_id' => 5,
    'judul' => 'Testing upload',
    'deskripsi' => NULL,
    'file_materi' => 'materi/PDF/H2VXzd7dzrawjvAU8zGdqZ693aylPGRQxF5XtqZ2.pdf',
    'urutan_sub_materi' => 1,
    'created_at' => '2026-04-23 13:56:14',
    'updated_at' => '2026-04-23 13:56:14',
  ),
  9 => 
  array (
    'sub_materi_id' => 10,
    'materi_id' => 5,
    'judul' => 'Testing upload',
    'deskripsi' => 'test 1',
    'file_materi' => 'materi/PDF/sGsAuPpMpnNZxTKHviqD3WVPQEw8IJE34nixtwVC.pdf',
    'urutan_sub_materi' => 2,
    'created_at' => '2026-04-23 13:56:58',
    'updated_at' => '2026-04-24 16:55:56',
  ),
  10 => 
  array (
    'sub_materi_id' => 11,
    'materi_id' => 1,
    'judul' => 'Testing upload (edit tester)',
    'deskripsi' => '123',
    'file_materi' => 'materi/Video/19fCnxFA22m2VfrRHcT2TheP6ydAUG0Lsm7kusmX.mp4',
    'urutan_sub_materi' => 5,
    'created_at' => '2026-04-26 15:47:26',
    'updated_at' => '2026-05-03 16:16:25',
  ),
  11 => 
  array (
    'sub_materi_id' => 15,
    'materi_id' => 1,
    'judul' => 'Pelatihan Postman 1',
    'deskripsi' => 'updated',
    'file_materi' => 'materi/PPT/Sj6FCcVkBHTTFld7YU3xQQdTiwtLxT1HoMbe7wr5.pptx',
    'urutan_sub_materi' => 6,
    'created_at' => '2026-05-06 17:07:04',
    'updated_at' => '2026-05-06 17:14:40',
  ),
);
        if(count($data) > 0) {
            DB::table('sub_materis')->insert($data);
        }
    }
}
