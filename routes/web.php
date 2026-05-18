<?php

use Illuminate\Support\Facades\Route;

// user karyawan

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/', function () {
    return "Login berhasil (sementara)";
})->name('login.post');

Route::get('/pembelajaran', function () {
    return view('pembelajaran');
});

Route::get('/belum-mulai', function () {
    return view('belum-mulai');
});

Route::get('/materi-progress', function () {
    return view('materi-progress');
});

Route::get('/materi-selesai', function () {
    return view('materi-selesai');
});

Route::get('/detail-materi', function () {
    return view('detail-materi');
});

Route::get('/awal-submateri', function () {
    return view('dummy-awal-submateri');
});

Route::get('/lanjutkan-materi', function () {
    return view('lanjutkan-materi');
});

// Untuk lanjutkan materi
// Route::get('/lanjutkan-materi/{materi}/{submateri}', [MateriController::class, 'show']);

Route::get('/dummy-lanjutkan-materi-ver-ppt', function () {
    return view('dummy-lanjutkan-materi-ppt');
});

Route::get('/akhir-submateri', function () {
    return view('dummy-akhir-submateri');
});

Route::get('/final-kuis', function () {
    return view('materi-kuis');
});

Route::get('/hasil-kuis', function () {
    return view('hasil-kuis');
});

Route::get('/dummy-hasil-kuis-gagal', function () {
    return view('hasil-kuis-gagal');
});

// user superadmin
Route::get('/beranda-superadmin', function () {
    return view('beranda-superadmin', ['role' => 'superadmin']);
});

Route::get('/detail-leaderboard', function () {
    return view('detail-leaderboard');
});

Route::get('/manajemen-pengguna', function () {
    return view('manajemen-pengguna');
});

Route::get('/tambah-peran', function () {
    return view('tambah-peran');
});

Route::get('/manajemen-unit-kerja', function () {
    return view('manajemen-unit-kerja');
});

Route::get('/manajemen-pelatihan', function () {
    return view('manajemen-pelatihan', ['role' => 'superadmin']);
});

Route::get('/daftar-materi-kuis', function () {
    return view('daftar-materi-kuis-superadmin', ['role' => 'superadmin']);
});

Route::get('/arsip-pelatihan', function () {
    return view('arsip-pelatihan', ['role' => 'superadmin']);
});

Route::get('/sampah-pelatihan', function () {
    return view('sampah-pelatihan', ['role' => 'superadmin']);
});

Route::get('/manajemen-kategori', function () {
    return view('manajemen-kategori');
});

Route::get('/laporan-monitoring', function () {
    return view('laporan-monitoring', ['role' => 'superadmin']);
});

Route::get('/log-aktivitas', function () {
    return view('log-aktivitas');
});

Route::get('/cadangan', function () {
    return view('cadangan');
});

Route::get('/admin/beranda', function () {
    return view('beranda-admin-teacher', ['role' => 'admin']);
});

Route::get('/admin/laporan-monitoring', function () {
    return view('laporan-monitoring', ['role' => 'admin']);
});

Route::get('/validasi-pelatihan', function () {
    return view('validasi-pelatihan');
});

Route::get('/review-pelatihan', function () {
    return view('review-pelatihan');
});

Route::get('/kelola-ttd', function () {
    return view('kelola-ttd');
});

// Route untuk Teacher
Route::get('/teacher/beranda', function () {
    return view('beranda-admin-teacher', ['role' => 'teacher']);
});

Route::get('/teacher/manajemen-pelatihan', function () {
    return view('manajemen-pelatihan', ['role' => 'teacher']);
});

Route::get('/teacher/daftar-materi-kuis', function () {
    return view('daftar-materi-kuis-superadmin', ['role' => 'teacher']);
});

Route::get('/teacher/arsip-pelatihan', function () {
    return view('arsip-pelatihan', ['role' => 'teacher']);
});

Route::get('/teacher/sampah-pelatihan', function () {
    return view('sampah-pelatihan', ['role' => 'teacher']);
});

