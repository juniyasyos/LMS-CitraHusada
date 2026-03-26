<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // ambil semua user
        $users = User::all();

        foreach ($users as $user) {

            // Materi Baru
            Notification::create([
                'user_id' => $user->user_id,
                'type' => 'materi_baru',
                'title' => 'Materi Baru Tersedia',
                'message' => 'Materi "Keselamatan Pasien" telah ditambahkan',
                'is_read' => false,
                'data' => [
                    'materi_id' => 1
                ]
            ]);

            // Deadline
            Notification::create([
                'user_id' => $user->user_id,
                'type' => 'deadline',
                'title' => 'Deadline Mendekat',
                'message' => 'Materi "Manajemen Rumah Sakit" akan berakhir dalam 7 hari',
                'is_read' => false,
                'data' => [
                    'materi_id' => 2
                ]
            ]);

            // Sertifikat
            Notification::create([
                'user_id' => $user->user_id,
                'type' => 'sertifikat',
                'title' => 'Sertifikat Tersedia',
                'message' => 'Sertifikat pelatihan sudah bisa diunduh',
                'is_read' => false,
                'data' => [
                    'materi_id' => 3
                ]
            ]);
        }
    }
}