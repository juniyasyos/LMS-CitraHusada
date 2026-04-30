<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');

// Rute Terproteksi (Hanya yang sudah Login via Session Web)
Route::middleware('auth')->group(function () {
    Route::get('/pembelajaran', function () { return view('pembelajaran'); })->name('pembelajaran');
        Route::get('/detail-materi/{materiId}', function ($materiId) { return view('detail-materi', compact('materiId')); });
        Route::get('/lanjutkan-materi/{materiId}', function ($materiId) { return view('lanjutkan-materi', compact('materiId')); });
        Route::get('/post-test/{materiId}', function ($materiId) { return view('materi-kuis', compact('materiId')); });
        
        Route::get('/lanjut', function () { return view('lanjutkan-materi'); });
        Route::get('/detail', function () { return view('detail-materi'); });
        // Route::get('/lanjut-ppt', function () { return view('dummu-lanjutkan-materi-ppt'); });
        Route::get('/materi-kuis', function () { return view('materi-kuis'); });
        // Route::get('/materi-kuis-akhir', function () { return view('materi-kuis-akhir'); });
        // Route::get('/hasil-kuis-gagal', function () { return view('hasil-kuis-gagal'); });
        Route::get('/hasil-kuis', function () { return view('hasil-kuis'); });
        // Route::get('/pembelajaran-new', function () { return view('pembelajaran-new'); });
        // Route::get('/materi-kuis-new', function () { return view('materi-kuis-new'); });


        
    // Group Karyawan (Misal role_id = 4)
    Route::middleware('role:4')->group(function () {
        Route::get('/pembelajaran', function () { return view('pembelajaran'); })->name('pembelajaran');
        Route::get('/detail-materi/{materiId}', function ($materiId) { return view('detail-materi', compact('materiId')); });
        Route::get('/lanjutkan-materi/{materiId}', function ($materiId) { return view('lanjutkan-materi', compact('materiId')); });
        Route::get('/post-test/{materiId}', function ($materiId) { return view('materi-kuis', compact('materiId')); });
        
        Route::get('/lanjut', function () { return view('lanjutkan-materi'); });
        Route::get('/detail', function () { return view('detail-materi'); });
        // Route::get('/lanjut-ppt', function () { return view('dummu-lanjutkan-materi-ppt'); });
        Route::get('/materi-kuis', function () { return view('materi-kuis'); });
        // Route::get('/materi-kuis-akhir', function () { return view('materi-kuis-akhir'); });
        // Route::get('/hasil-kuis-gagal', function () { return view('hasil-kuis-gagal'); });
        Route::get('/hasil-kuis', function () { return view('hasil-kuis'); });
        // Route::get('/pembelajaran-new', function () { return view('pembelajaran-new'); });
        // Route::get('/materi-kuis-new', function () { return view('materi-kuis-new'); });
    });

    // Group Superadmin (Misal role_id = 1)
    Route::middleware('role:1')->group(function () {
        //Karyawan
        Route::get('/pembelajaran', function () { return view('pembelajaran'); })->name('pembelajaran');
        Route::get('/detail-materi/{materiId}', function ($materiId) { return view('detail-materi', compact('materiId')); });
        Route::get('/lanjutkan-materi/{materiId}', function ($materiId) { return view('lanjutkan-materi', compact('materiId')); });
        Route::get('/post-test/{materiId}', function ($materiId) { return view('materi-kuis', compact('materiId')); });
        Route::get('/lanjut', function () { return view('lanjutkan-materi'); });
        Route::get('/detail', function () { return view('detail-materi'); });
        Route::get('/materi-kuis', function () { return view('materi-kuis'); });
        Route::get('/hasil-kuis', function () { return view('hasil-kuis'); });
        //Beranda
        Route::get('/beranda-superadmin', [\App\Http\Controllers\DashboardSuperadminController::class, 'index'])->name('beranda-superadmin');
        // Manajemen Cadangan
        Route::get('/cadangan', [App\Http\Controllers\BackupController::class, 'index'])->name('cadangan');
        Route::post('/cadangan/run', [App\Http\Controllers\BackupController::class, 'runBackup'])->name('cadangan.run');
        Route::post('/cadangan/settings', [App\Http\Controllers\BackupController::class, 'updateSettings'])->name('cadangan.settings');
        Route::post('/cadangan/delete-selected', [App\Http\Controllers\BackupController::class, 'deleteSelected'])->name('cadangan.delete-selected');
        Route::post('/cadangan/reset', [App\Http\Controllers\BackupController::class, 'reset'])->name('cadangan.reset');
        Route::get('/laporan-monitoring', [\App\Http\Controllers\LaporanMonitoringController::class, 'index'])->name('laporan.monitoring');
        Route::get('/laporan-monitoring/export/excel', [\App\Http\Controllers\LaporanMonitoringController::class, 'exportExcel'])->name('laporan.monitoring.excel');
        Route::get('/laporan-monitoring/export/pdf', [\App\Http\Controllers\LaporanMonitoringController::class, 'exportPdf'])->name('laporan.monitoring.pdf');
        Route::get('/manajemen-unit-kerja-update', function () { return view('SuperAdmin_Views.manajemen-unit-kerja-update'); });
        
        Route::get('/cadangan-update', function () { return view('SuperAdmin_Views.cadangan-update'); });
        
        Route::get('/log-aktivitas', [\App\Http\Controllers\LogAktivitasController::class, 'index'])->name('log-aktivitas');
        Route::get('/log-aktivitas/export', [\App\Http\Controllers\LogAktivitasController::class, 'export'])->name('log-aktivitas.export');
        Route::get('/manajemen-kategori', [\App\Http\Controllers\KategoriController::class, 'index'])->name('manajemen-kategori');
        Route::post('/manajemen-kategori', [\App\Http\Controllers\KategoriController::class, 'store'])->name('manajemen-kategori.store');
        Route::put('/manajemen-kategori/{id}', [\App\Http\Controllers\KategoriController::class, 'update'])->name('manajemen-kategori.update');
        Route::delete('/manajemen-kategori/{id}', [\App\Http\Controllers\KategoriController::class, 'destroy'])->name('manajemen-kategori.destroy');
        Route::get('/manajemen-pengguna', [\App\Http\Controllers\ManajemenPenggunaController::class, 'index'])->name('manajemen-pengguna');
        Route::put('/manajemen-pengguna/{id}', [\App\Http\Controllers\ManajemenPenggunaController::class, 'update'])->name('manajemen-pengguna.update');
        Route::delete('/manajemen-pengguna/{id}', [\App\Http\Controllers\ManajemenPenggunaController::class, 'destroy'])->name('manajemen-pengguna.destroy');
        Route::get('/manajemen-pengguna/impersonate/{id}', [\App\Http\Controllers\ManajemenPenggunaController::class, 'impersonate'])->name('manajemen-pengguna.impersonate');
        Route::get('/manajemen-unit-kerja', [\App\Http\Controllers\UnitKerjaController::class, 'index'])->name('manajemen-unit-kerja');
        Route::post('/manajemen-unit-kerja', [\App\Http\Controllers\UnitKerjaController::class, 'store'])->name('manajemen-unit-kerja.store');
        Route::put('/manajemen-unit-kerja/{id}', [\App\Http\Controllers\UnitKerjaController::class, 'update'])->name('manajemen-unit-kerja.update');
        Route::delete('/manajemen-unit-kerja/{id}', [\App\Http\Controllers\UnitKerjaController::class, 'destroy'])->name('manajemen-unit-kerja.destroy');
        // Route::get('/manajemen-pengguna-new', function () { return view('manajemen-pengguna'); });
        Route::get('/detail-leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('detail-leaderboard');
        Route::get('/leaderboard/export/pdf', [\App\Http\Controllers\LeaderboardController::class, 'exportPdf'])->name('leaderboard.export.pdf');
        Route::get('/leaderboard/export/excel', [\App\Http\Controllers\LeaderboardController::class, 'exportExcel'])->name('leaderboard.export.excel');
        Route::get('/tambah-peran', [\App\Http\Controllers\ManajemenPenggunaController::class, 'create'])->name('tambah-peran');
        Route::post('/tambah-peran', [\App\Http\Controllers\ManajemenPenggunaController::class, 'store'])->name('tambah-peran.store');
        
        // Manajemen Pelatihan
        Route::get('/manajemen-pelatihan', [\App\Http\Controllers\ManajemenPelatihanController::class, 'index'])->name('manajemen-pelatihan');
        Route::post('/manajemen-pelatihan', [\App\Http\Controllers\ManajemenPelatihanController::class, 'store'])->name('pelatihan.store');
        Route::post('/manajemen-pelatihan/{id}/archive', [\App\Http\Controllers\ManajemenPelatihanController::class, 'archive'])->name('pelatihan.archive');
        Route::get('/arsip-pelatihan', [\App\Http\Controllers\ManajemenPelatihanController::class, 'archiveIndex'])->name('pelatihan.arsip');
        Route::get('/sub-materi/{id}/download', [\App\Http\Controllers\ManajemenPelatihanController::class, 'downloadSubMateri'])->name('pelatihan.downloadSubMateri');
        Route::put('/sub-materi/{id}', [\App\Http\Controllers\ManajemenPelatihanController::class, 'updateSubMateri'])->name('pelatihan.updateSubMateri');
        Route::delete('/sub-materi/{id}', [\App\Http\Controllers\ManajemenPelatihanController::class, 'destroySubMateri'])->name('pelatihan.destroySubMateri');
        Route::put('/post-test/{id}', [\App\Http\Controllers\ManajemenPelatihanController::class, 'updatePostTest'])->name('pelatihan.updatePostTest');
        Route::delete('/post-test/{id}', [\App\Http\Controllers\ManajemenPelatihanController::class, 'destroyPostTest'])->name('pelatihan.destroyPostTest');
        Route::post('/arsip-pelatihan/{id}/restore', [\App\Http\Controllers\ManajemenPelatihanController::class, 'unarchive'])->name('pelatihan.unarchive');
        Route::delete('/arsip-pelatihan/{id}', [\App\Http\Controllers\ManajemenPelatihanController::class, 'destroyFromArchive'])->name('pelatihan.destroyArchive');
        Route::put('/manajemen-pelatihan/{id}', [\App\Http\Controllers\ManajemenPelatihanController::class, 'update'])->name('pelatihan.update');
        Route::delete('/manajemen-pelatihan/{id}', [\App\Http\Controllers\ManajemenPelatihanController::class, 'destroy'])->name('pelatihan.destroy');
        Route::get('/sampah-pelatihan', [\App\Http\Controllers\ManajemenPelatihanController::class, 'trash'])->name('pelatihan.trash');
        Route::post('/sampah-pelatihan/{id}/restore', [\App\Http\Controllers\ManajemenPelatihanController::class, 'restore'])->name('pelatihan.restore');
        Route::delete('/sampah-pelatihan/{id}/force', [\App\Http\Controllers\ManajemenPelatihanController::class, 'forceDestroy'])->name('pelatihan.forceDestroy');
        Route::get('/daftar-materi-kuis/{materiId}', [\App\Http\Controllers\ManajemenPelatihanController::class, 'showMateriContent'])->name('daftar-materi-kuis');
        Route::post('/daftar-materi-kuis/{materiId}/sub-materi', [\App\Http\Controllers\ManajemenPelatihanController::class, 'storeSubMateri'])->name('pelatihan.storeSubMateri');
        Route::post('/daftar-materi-kuis/{materiId}/quiz', [\App\Http\Controllers\ManajemenPelatihanController::class, 'storePostTest'])->name('pelatihan.storePostTest');
        });

    Route::get('/impersonate/stop', [\App\Http\Controllers\ManajemenPenggunaController::class, 'stopImpersonating'])->name('impersonate.stop');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});