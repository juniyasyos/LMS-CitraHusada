<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DevApiDocsController;

if (config('iam.enabled')) {
    Route::get('/', function (\Illuminate\Http\Request $request) {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $redirectUrl = ($user->role_id == 1) ? '/beranda-superadmin' : '/pembelajaran';
            return redirect($redirectUrl);
        }
        return app(\Juniyasyos\IamClient\Http\Controllers\SsoLoginRedirectController::class)($request);
    })->name('login');
} else {
    // IAM/SSO disabled → gunakan login lokal (NIP + password)
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.post');
}

// Rute Terproteksi
$authMiddleware = config('iam.enabled') ? 'iam.auth:web' : 'auth';
Route::middleware($authMiddleware)->group(function () {
    Route::get('/dev/api-docs', [DevApiDocsController::class, 'index'])
        ->middleware('can:view-api-docs')
        ->name('dev.api-docs');

    // Sisi Karyawan / Pembelajaran (Dapat diakses semua role yang login)
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

    Route::get('/lanjut', function () {
        return view('lanjutkan-materi');
    });
    Route::get('/detail', function () {
        return view('detail-materi');
    });
    Route::get('/materi-kuis', function () {
        return view('materi-kuis');
    });
    Route::get('/hasil-kuis', function () {
        return view('hasil-kuis');
    });
    Route::get('/sertifikat', [\App\Http\Controllers\Api\SertifikatController::class, 'showUserSertifikat'])->name('sertifikat.index');
    Route::post('/sertifikat/eksternal', [\App\Http\Controllers\Api\SertifikatController::class, 'uploadSertifikatEksternal'])->name('sertifikat.eksternal.upload');


    // Group Superadmin (Misal role_id = 1)
    Route::middleware('role:super_admin')->group(function () {

        // Rute Administratif Superadmin (DIPROTEKSI: Akses ditolak saat mode impersonasi)
        Route::middleware('no.impersonate')->group(function () {

            // Beranda
            Route::get('/beranda-superadmin', function () {
                return view('SuperAdmin_Views.beranda-superadmin');
            })->name('beranda-superadmin');



            // Detail Leaderboard
            Route::get('/detail-leaderboard', function () {
                return view('SuperAdmin_Views.detail-leaderboard');
            })->name('detail-leaderboard');
            Route::get('/leaderboard/export/pdf', [\App\Http\Controllers\Api\LeaderboardController::class, 'exportPdf'])->name('leaderboard.export.pdf');
            Route::get('/leaderboard/export/excel', [\App\Http\Controllers\Api\LeaderboardController::class, 'exportExcel'])->name('leaderboard.export.excel');

            // Manajemen Pengguna
            Route::get('/manajemen-pengguna', [\App\Http\Controllers\Api\ManajemenPenggunaController::class, 'index'])->name('manajemen-pengguna');
            Route::get('/manajemen-pengguna/impersonate/{id}', [\App\Http\Controllers\Api\ManajemenPenggunaController::class, 'impersonate'])->name('manajemen-pengguna.impersonate');
            Route::get('/tambah-peran', function () {
                $unit_kerjas = \App\Models\UnitKerja::all();
                $jenis_tenagas = \App\Models\JenisTenaga::all();
                $roles = \App\Models\Role::all();
                return view('SuperAdmin_Views.tambah-peran', compact('unit_kerjas', 'jenis_tenagas', 'roles'));
            })->name('tambah-peran');

            // Manajemen Cadangan
            Route::get('/cadangan', function () {
                return view('SuperAdmin_Views.cadangan');
            })->name('cadangan');



            //Unit Kerja
            Route::get('/manajemen-unit-kerja-update', function () {
                return view('SuperAdmin_Views.manajemen-unit-kerja-update');
            });
            Route::get('/manajemen-unit-kerja', function () {
                return view('SuperAdmin_Views.manajemen-unit-kerja');
            })->name('manajemen-unit-kerja');

            //Log Aktivitas
            Route::get('/log-aktivitas', function () {
                return view('SuperAdmin_Views.log-aktivitas');
            })->name('log-aktivitas');
            Route::get('/log-aktivitas/export', [\App\Http\Controllers\Api\LogAktivitasController::class, 'export'])->name('log-aktivitas.export');

            // Kategori
            Route::get('/manajemen-kategori', function () {
                return view('SuperAdmin_Views.manajemen-kategori');
            })->name('manajemen-kategori');

            // // Manajemen Media
            // Route::get('/manajemen-pelatihan', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'index'])->name('manajemen-pelatihan');
            // Route::get('/arsip-pelatihan', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'archiveIndex'])->name('pelatihan.arsip');
            // Route::get('/sampah-pelatihan', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'trash'])->name('pelatihan.trash');
            // Route::get('/daftar-materi-kuis/{materiId}', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'showMateriContent'])->name('daftar-materi-kuis');

            // Note: Download route is intentionally left in web.php or can be moved to API if needed. For now, it stays here if it relies on session.
            // Route::get('/sub-materi/{id}/download', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'downloadSubMateri'])->name('pelatihan.downloadSubMateri');
        }); // END no.impersonate

        // Rute ini tetap di group Role:1 tapi di luar no.impersonate agar bisa Stop Impersonate
        Route::get('/impersonate/stop', [\App\Http\Controllers\Api\ManajemenPenggunaController::class, 'stopImpersonating'])->name('impersonate.stop');
    });

    Route::middleware('role:admin')->group(function () {
        Route::middleware('no.impersonate')->group(function () {

            Route::get('/kelola-ttd', function () {
                return view('Admin_Views.kelola-ttd');
            })->name('kelola-ttd');

            Route::get('/validasi-pelatihan/{sertifikatEksternalId}', [\App\Http\Controllers\Api\LaporanMonitoringController::class, 'showReviewPelatihan'])->name('admin.review-pelatihan');
            
            Route::get('/verifikasi-pelatihan/{userId}/{materiId}', [\App\Http\Controllers\Api\SertifikatController::class, 'showValidasi'])->name('admin.validasi-pelatihan');
        });
    });

    // Shared routes untuk Role 1 (Superadmin) dan Role 2 (Admin)
    Route::middleware(['role:super_admin,admin', 'no.impersonate'])->group(function () {
        
        // Single Laporan Monitoring route pointing to LaporanMonitoringController@index
        Route::get('/laporan-monitoring', [\App\Http\Controllers\Api\LaporanMonitoringController::class, 'index'])->name('laporan.monitoring');

        Route::get('/laporan-monitoring/export/excel', [\App\Http\Controllers\Api\LaporanMonitoringController::class, 'exportExcel'])->name('laporan.monitoring.excel');
        Route::get('/laporan-monitoring/export/pdf', [\App\Http\Controllers\Api\LaporanMonitoringController::class, 'exportPdf'])->name('laporan.monitoring.pdf');
        
        // Sertifikat Eksternal Detail per User
        Route::get('/sertifikat-eksternal/{userId}', [\App\Http\Controllers\Api\LaporanMonitoringController::class, 'showSertifikatEksternal'])->name('sertifikat.eksternal.show');
        Route::get('/sertifikat-eksternal/{userId}/export/excel', [\App\Http\Controllers\Api\LaporanMonitoringController::class, 'exportSertifikatEksternalExcel'])->name('sertifikat.eksternal.export.excel');
        Route::get('/sertifikat-eksternal/{userId}/export/pdf', [\App\Http\Controllers\Api\LaporanMonitoringController::class, 'exportSertifikatEksternalPdf'])->name('sertifikat.eksternal.export.pdf');

        // Validasi Pelatihan
    });

    Route::middleware('role:admin,teacher')->group(function () {
        Route::middleware('no.impersonate')->group(function () {
            // DASHBOARD ADMIN
            Route::get('/beranda-admin', function () {
                return view('Admin_Views.beranda-admin-teacher');
            })->name('beranda-admin-teacher');

            Route::get('/admin/dashboard/export/pdf', [\App\Http\Controllers\Api\DashboardAdminController::class, 'exportPdf'])->name('admin.dashboard.export.pdf');
            Route::get('/admin/dashboard/export/excel', [\App\Http\Controllers\Api\DashboardAdminController::class, 'exportExcel'])->name('admin.dashboard.export.excel');
        });
    });

    Route::middleware('role:super_admin,teacher')->group(function () {
        Route::middleware('no.impersonate')->group(function () {
           // Manajemen Media
            Route::get('/manajemen-pelatihan', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'index'])->name('manajemen-pelatihan');
            Route::get('/arsip-pelatihan', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'archiveIndex'])->name('pelatihan.arsip');
            Route::get('/sampah-pelatihan', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'trash'])->name('pelatihan.trash');
            Route::get('/daftar-materi-kuis/{materiId}', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'showMateriContent'])->name('daftar-materi-kuis');
            Route::get('/arsip-pelatihan/daftar-materi-kuis/{materiId}', [\App\Http\Controllers\Api\ManajemenPelatihanController::class, 'showArchivedMateriContent'])->name('arsip.daftar-materi-kuis');
        });
    });

    // Logout bersifat global untuk semua role yang login
    if (config('iam.enabled')) {
        Route::post('/logout', \Juniyasyos\IamClient\Http\Controllers\LogoutController::class)->name('logout');
    } else {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    }

}); // END auth