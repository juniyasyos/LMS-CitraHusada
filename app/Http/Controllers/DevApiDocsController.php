<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevApiDocsController extends Controller
{
    public function index(Request $request)
    {
        $authGuide = [
            'description' => 'Semua API yang dilindungi menggunakan Laravel Sanctum. Buat token melalui login API dan gunakan header Authorization Bearer.',
            'example' => [
                'method' => 'POST',
                'url' => '/api/login',
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'body' => [
                    'nip' => 'user@example.com',
                    'password' => 'password',
                ],
                'response' => [
                    'success' => true,
                    'message' => 'Login berhasil',
                    'data' => [
                        'token' => 'eyJ0eXAiOiJKV1QiLCJh...'
                    ],
                ],
            ],
            'token_usage' => [
                'header' => 'Authorization: Bearer {token}',
                'additional' => 'Pastikan token disimpan dengan aman dan digunakan pada setiap permintaan API yang memerlukan auth:sanctum.',
            ],
        ];

        $groups = [
            [
                'title' => 'Authentication',
                'description' => 'Endpoint auth umum untuk API.',
                'items' => [
                    [
                        'method' => 'POST',
                        'uri' => '/api/login',
                        'description' => 'Login API dan terima Bearer token Sanctum.',
                        'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
                        'body' => ['nip' => 'string', 'password' => 'string'],
                        'response' => ['success' => true, 'message' => 'Login berhasil', 'data' => ['token' => 'string']],
                    ],
                    [
                        'method' => 'POST',
                        'uri' => '/api/logout',
                        'description' => 'Hapus token saat logout.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'message' => 'Logout berhasil'],
                    ],
                    [
                        'method' => 'GET',
                        'uri' => '/api/check-auth',
                        'description' => 'Cek status autentikasi pengguna dengan token.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'message' => 'User ter-autentikasi', 'data' => ['user' => ['user_id' => 1, 'nama' => '...']]],
                    ],
                ],
            ],
            [
                'title' => 'User Profile',
                'description' => 'Endpoint untuk mengambil data profil pengguna.',
                'items' => [
                    [
                        'method' => 'GET',
                        'uri' => '/api/profile',
                        'description' => 'Ambil data profil pengguna terautentikasi.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['user' => ['user_id' => 1, 'nama' => '...']]],
                    ],
                    [
                        'method'=>'GET',
                        'uri'=>'/api/notifications',
                        'description' => 'Ambil daftar notifikasi pengguna.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['id' => 1, 'user_id' => 1, 'type' => 'deadline_reminder', 'message' => 'Deadline Materi: materi 4','is_read' => false, 'created_at' => '...']],
                    ]
                ],
            ],
            [
                'title' => 'Manajemen Pengguna',
                'description' => 'CRUD pengguna untuk Superadmin.',
                'items' => [
                    [
                        'method' => 'GET',
                        'uri' => '/api/admin/manajemen-pengguna',
                        'description' => 'Ambil daftar pengguna.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['users' => []]],
                    ],
                    [
                        'method' => 'POST',
                        'uri' => '/api/admin/manajemen-pengguna',
                        'description' => 'Buat pengguna baru.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json', 'Content-Type' => 'application/json'],
                        'body' => ['nama' => 'string', 'nip' => 'string', 'password' => 'string', 'role_id' => 1, 'unit_kerja_id' => 1, 'jenis_tenaga_id' => 1],
                        'default_body' => '{"nama":"John Doe","nip":"johndoe@example.com","password":"password123","role_id":4,"unit_kerja_id":1,"jenis_tenaga_id":1}',
                        'response' => ['success' => true, 'message' => 'User berhasil dibuat', 'data' => ['user' => []]],
                    ],
                    [
                        'method' => 'PUT',
                        'uri' => '/api/admin/manajemen-pengguna/{id}',
                        'description' => 'Perbarui data pengguna.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json', 'Content-Type' => 'application/json'],
                        'body' => ['nama' => 'string', 'role_id' => 1],
                        'default_body' => '{"nama":"Updated Name","role_id":2}',
                        'response' => ['success' => true, 'message' => 'User berhasil diupdate', 'data' => ['user' => []]],
                    ],
                    [
                        'method' => 'DELETE',
                        'uri' => '/api/admin/manajemen-pengguna/{id}',
                        'description' => 'Hapus pengguna.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'message' => 'User berhasil dihapus'],
                    ],
                ],
            ],
            [
                'title' => 'Pelatihan',
                'description' => 'Endpoint materi dan progress karyawan.',
                'items' => [
                    [
                        'method' => 'GET',
                        'uri' => '/api/materi-user',
                        'description' => 'Ambil daftar materi untuk pengguna terautentikasi.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['materi' => []]],
                    ],
                    [
                        'method' => 'GET',
                        'uri' => '/api/materi-user/{id}',
                        'description' => 'Ambil detail materi.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['materi' => []]],
                    ],
                    [
                        'method' => 'GET',
                        'uri' => '/api/materi-lanjutkan/{id}',
                        'description' => 'Ambil progress lanjutan materi.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['progress' => []]],
                    ],
                    [
                        'method' => 'POST',
                        'uri' => '/api/progress/update',
                        'description' => 'Update progress materi pengguna.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json', 'Content-Type' => 'application/json'],
                        'body' => ['materi_id' => 1, 'progress' => 2],
                        'response' => ['success' => true, 'message' => 'Progress berhasil diperbarui'],
                    ],
                ],
            ],
            [
                'title' => 'Leaderboard',
                'description' => 'Endpoint leaderboard khusus Superadmin.',
                'items' => [
                    [
                        'method' => 'GET',
                        'uri' => '/api/admin/leaderboard/data',
                        'description' => 'Ambil data leaderboard.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['leaderboard' => []]],
                    ],
                ],
            ],
            [
                'title' => 'Backup & Monitoring',
                'description' => 'Superadmin-only system maintenance endpoints.',
                'items' => [
                    [
                        'method' => 'GET',
                        'uri' => '/api/admin/backup/data',
                        'description' => 'Ambil status backup.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['backup' => []]],
                    ],
                    [
                        'method' => 'POST',
                        'uri' => '/api/admin/backup/run',
                        'description' => 'Jalankan proses backup manual.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'message' => 'Backup berjalan'],
                    ],
                    [
                        'method' => 'GET',
                        'uri' => '/api/admin/laporan-monitoring/data',
                        'description' => 'Ambil data laporan monitoring.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'body' => null,
                        'response' => ['success' => true, 'data' => ['monitoring' => []]],
                    ],
                ],
            ],
            [
                'title' => 'Log Aktivitas',
                'description' => 'Endpoint log aktivitas untuk Superadmin.',
                'items' => [
                    [
                        'method' => 'GET',
                        'uri' => '/api/admin/log-aktivitas',
                        'description' => 'Ambil data log aktivitas dengan filter.',
                        'headers' => ['Authorization' => 'Bearer {token}', 'Accept' => 'application/json'],
                        'query_params' => ['search' => 'string', 'tanggal' => 'YYYY-MM-DD', 'tipe' => 'string|array'],
                        'response' => ['success' => true, 'data' => ['logs' => []]],
                    ],
                ],
            ],
        ];

        $recommendation = 'Saya merekomendasikan untuk menulis dokumentasi ini secara manual di Blade terlebih dahulu, karena
paket otomatis seperti knuckleswtf/scribe dapat membantu jika API controller dan response schema sudah konsisten.
Pada tahap awal, dokumentasi manual memberikan kontrol penuh atas format, bahasa, dan contoh respons yang akurat.';

        return view('dev.api-docs', compact('authGuide', 'groups', 'recommendation'));
    }
}
