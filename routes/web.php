<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');

// Kita hapus middleware 'auth' bawaan Laravel karena proteksi sekarang 100% dari Frontend (Javascript Token)
Route::group([], function () {
    Route::get('/pembelajaran', function () {
        return view('pembelajaran');
    })->name('pembelajaran');
    
    Route::get('/detail-materi/{materiId}', function ($materiId) {
        return view('detail-materi', compact('materiId'));
    });

    Route::get('/lanjutkan-materi/{materiId}', function ($materiId) {
        return view('lanjutkan-materi', compact('materiId'));
    });

    Route::get('/post-test/{materiId}', function ($materiId) {
        return view('materi-kuis', compact('materiId'));
    });
//##############################################
    Route::get('/lanjut', function () {
        return view('lanjutkan-materi');
    });
    Route::get('/detail', function () {
        return view('detail-materi');
    });
    Route::get('/lanjut-ppt', function () {
        return view('dummu-lanjutkan-materi-ppt');
    });
    Route::get('/materi-kuis', function () {
        return view('materi-kuis');
    });
    Route::get('/materi-kuis-akhir', function () {
        return view('materi-kuis-akhir');
    });
    Route::get('/hasil-kuis-gagal', function () {
        return view('hasil-kuis-gagal');
    });
    Route::get('/hasil-kuis', function () {
        return view('hasil-kuis');
    });
    Route::get('/pembelajaran-new', function () {
        return view('pembelajaran-new');
    });
    Route::get('/materi-kuis-new', function () {
        return view('materi-kuis-new');
    });
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});