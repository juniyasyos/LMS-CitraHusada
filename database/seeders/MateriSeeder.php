<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserMateriSeeder extends Seeder
{
    public function run(): void
    {

        // ====================
        // Materis
        // ====================

        $materis = [
            [
                'name' => 'Admin LMS',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => 1
            ],
            [
                'name' => 'User Demo',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => 2
            ]
        ];

        foreach ($materis as $materi) {
            DB::table('materis')->updateOrInsert(
                ['email' => $materi['email']],
                $materi + [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }



        // ====================
        // DATA MATERI
        // ====================

        $materi = [
            'Pengenalan LMS',
            'Keselamatan Pasien',
            'Standar Pelayanan Rumah Sakit'
        ];

        foreach ($materi as $m) {
            DB::table('materi')->updateOrInsert(
                ['judul_materi' => $m],
                [
                    'judul_materi' => $m,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }



        // ====================
        // DATA SUB MATERI
        // ====================

        $subMateri = [
            [
                'materi_id' => 1,
                'judul_sub_materi' => 'Apa itu LMS'
            ],
            [
                'materi_id' => 1,
                'judul_sub_materi' => 'Cara Menggunakan LMS'
            ],
            [
                'materi_id' => 2,
                'judul_sub_materi' => 'Prinsip Keselamatan Pasien'
            ]
        ];

        foreach ($subMateri as $sub) {
            DB::table('sub_materi')->updateOrInsert(
                ['judul_sub_materi' => $sub['judul_sub_materi']],
                $sub + [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}