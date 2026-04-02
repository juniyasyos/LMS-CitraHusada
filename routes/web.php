<?php

use Illuminate\Support\Facades\Route;

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