<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PembelajaranController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\MateriUserController;
use App\Http\Controllers\Api\DashboardSuperadminController;
use App\Http\Controllers\Api\DashboardAdminController;
use App\Http\Controllers\Api\BackupController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\LaporanMonitoringController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\ManajemenPenggunaController;
use App\Http\Controllers\Api\UnitKerjaController;
use App\Http\Controllers\Api\LogAktivitasController;
use App\Http\Controllers\Api\ManajemenPelatihanController;
use App\Http\Controllers\Api\SertifikatController;
use App\Http\Controllers\Api\RestoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::post('/login', [AuthController::class, 'loginApi']);

// Public API routes
Route::post('/login', [AuthController::class, 'loginApi']);

// Debug: Check users for API test page
Route::get('/debug-users', function () {
    $users = \App\Models\User::select('user_id', 'nik', 'nama', 'role_id')->get();
    return response()->json([
        'success' => true,
        'message' => 'Available users for testing (password: "password")',
        'data' => $users
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    // Global User Endpoint
    Route::post('/logout', [AuthController::class, 'logoutApi']);
    Route::get('/check-auth', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'User ter-autentikasi',
            'data' => ['user' => $request->user()]
        ]);
    });

    // ============================================
    // API UNTUK KARYAWAN (role_id = 4)
    // ============================================
    // Route::middleware('role:4')->group(function () {
    // });

    // Profile & Progress
    Route::get('/profile', [PembelajaranController::class, 'getProfile']);

    // Materi
    Route::get('/materi-user', [MateriUserController::class, 'index']);
    Route::get('/materi-user/{id}', [MateriUserController::class, 'show']);
    Route::get('/materi-lanjutkan/{id}', [MateriUserController::class, 'lanjutkan']);
    Route::post('/progress/update', [MateriUserController::class, 'updateProgress']);

    // Kuis / Post-Test
    Route::get('/post-test-soal/{materiId}', [MateriUserController::class, 'getSoalPostTest']);
    Route::post('/post-test-start', [MateriUserController::class, 'startPostTest']);
    Route::post('/post-test-submit', [MateriUserController::class, 'submitPostTest']);
    // ============================================
    // ROUTE NOTIFIKASI (Global untuk Semua Role yang Login)
    // ============================================
    // (Logic separasi data admin dan karyawan sudah ditangani aman oleh Controller via filter column `notif_admin`)
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::get('/notifications/count', [NotificationController::class, 'countUnread']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    // ============================================
    // API UNTUK SUPERADMIN (role_id = 1)
    // ============================================
    Route::middleware(['role:1', 'no.impersonate'])->prefix('admin')->group(function () {
        // Dashboard
        // Route::get('/dashboard/charts', [\App\Http\Controllers\DashboardSuperadminController::class, 'getChartData'])->name('api.dashboard.charts');
        Route::get('/superadmin/dashboard', [DashboardSuperadminController::class, 'index']);
        Route::get('/superadmin/dashboard/charts', [DashboardSuperadminController::class, 'getChartData']);
        // Route::get('/karyawan-progress', [DashboardSuperadminController::class, 'getKaryawanProgress']);

        // Manajemen Pengguna
        Route::prefix('manajemen-pengguna')->group(function () {
            // Read Data
            Route::get('/', [ManajemenPenggunaController::class, 'getData']);

            // Create, Update, Delete
            Route::post('/store', [ManajemenPenggunaController::class, 'store']);
            Route::put('/update/{id}', [ManajemenPenggunaController::class, 'update']);
            Route::delete('/destroy/{id}', [ManajemenPenggunaController::class, 'destroy']);
        });

        // Unit Kerja dan Jenis Tenaga
        Route::prefix('unit-kerja-management')->group(function () {
            Route::get('/', [UnitKerjaController::class, 'index']);      // Ambil data
            Route::post('/store', [UnitKerjaController::class, 'store']);     // Tambah data
            Route::put('/update/{id}', [UnitKerjaController::class, 'update']); // Update data
            Route::delete('/destroy/{id}', [UnitKerjaController::class, 'destroy']); // Hapus data
        });

        // Kategori
        Route::prefix('kategori')->group(function () {
            Route::get('/data', [KategoriController::class, 'getKategoriData']);
            Route::post('/store', [KategoriController::class, 'store']);
            Route::put('/update/{id}', [KategoriController::class, 'update']);
            Route::delete('/destroy/{id}', [KategoriController::class, 'destroy']);
        });

        //Backup/Cadangan
        Route::prefix('backup')->group(function () {
            Route::get('/data', [BackupController::class, 'getBackupData'])->name('api.backup.data');
            Route::get('/status', [BackupController::class, 'getBackupStatus'])->name('api.backup.status');
            Route::post('/run', [BackupController::class, 'runBackup'])->name('api.backup.run');
            Route::post('/settings', [BackupController::class, 'updateSettings'])->name('api.backup.settings');
            Route::post('/delete-selected', [BackupController::class, 'deleteSelected'])->name('api.backup.delete-selected');
            Route::post('/reset', [BackupController::class, 'reset'])->name('api.backup.reset');
        });

        //Restore Backup
        Route::prefix('restore')->group(function () {
            Route::get('/backups', [RestoreController::class, 'getBackupFiles'])->name('api.restore.backups');
            Route::get('/download', [RestoreController::class, 'downloadBackup'])->name('api.restore.download');
            Route::post('/run', [RestoreController::class, 'restore'])->name('api.restore.run');
            Route::get('/logs', [RestoreController::class, 'getRestoreLogs'])->name('api.restore.logs');
        });

        //Log Aktivitas
        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index']);

        //Detail Leaderboard
        Route::get('/leaderboard/data', [LeaderboardController::class, 'getLeaderboardDataApi']);

    });

    Route::middleware(['role:1,2', 'no.impersonate'])->prefix('admin')->group(function () {
        Route::get('/laporan-monitoring/data', [LaporanMonitoringController::class, 'getMonitoringData']);
        Route::get('/laporan-monitoring/sertifikat-eksternal', [LaporanMonitoringController::class, 'getSertifikatEksternalData']);
        Route::get('/laporan-monitoring/sertifikat-eksternal/list', [LaporanMonitoringController::class, 'getSertifikatEksternalList']);
        Route::get('/laporan-monitoring/sertifikat-eksternal/{userId}', [LaporanMonitoringController::class, 'getUserSertifikatEksternal']);
        // Route::get('/generate/{userId}/{materiId}', [SertifikatController::class, 'generateUserSertifikat'])->name('api.sertifikat.generate');
        Route::prefix('sertifikat')->group(function () {
            Route::get('/generate/{userId}/{materiId}', [SertifikatController::class, 'generateUserSertifikat']);
        });
    });

    Route::middleware(['role:2', 'no.impersonate'])->prefix('admin')->group(function () {
        // Kelola Tanda Tangan / Sertifikat
        Route::prefix('sertifikat')->group(function () {
            Route::get('/direktur', [SertifikatController::class, 'getDirektur']);
            Route::post('/direktur', [SertifikatController::class, 'updateDirektur']);
            Route::get('/preview', [SertifikatController::class, 'previewSertifikat']);
            Route::post('/validasi/{userId}/{materiId}', [SertifikatController::class, 'processValidasi']);
        });
        Route::post('/sertifikat-eksternal/verifikasi/{sertifikatEksternalId}', [LaporanMonitoringController::class, 'verifikasiSertifikatEksternal']);
    });
        
    Route::middleware(['role:2,3', 'no.impersonate'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index']);
        Route::get('/dashboard/charts', [DashboardAdminController::class, 'getChartData']);
        Route::get('/karyawan-progress', [DashboardAdminController::class, 'getKaryawanProgress']);
    });

    Route::middleware(['role:1,3', 'no.impersonate'])->prefix('admin')->group(function () {
        // Manajemen Pelatihan
        Route::prefix('manajemen-pelatihan')->group(function () {
            Route::get('/data', [ManajemenPelatihanController::class, 'getData']);
            Route::post('/', [ManajemenPelatihanController::class, 'store']);
            Route::put('/{id}', [ManajemenPelatihanController::class, 'update']);
            Route::delete('/{id}', [ManajemenPelatihanController::class, 'destroy']);

            Route::get('/arsip/data', [ManajemenPelatihanController::class, 'getArchiveData']);
            Route::post('/arsip/{id}/restore', [ManajemenPelatihanController::class, 'unarchive']);
            Route::delete('/arsip/{id}', [ManajemenPelatihanController::class, 'destroyFromArchive']);

            Route::get('/sampah/data', [ManajemenPelatihanController::class, 'getTrashData']);
            Route::post('/sampah/{id}/restore', [ManajemenPelatihanController::class, 'restore']);
            Route::delete('/sampah/{id}/force', [ManajemenPelatihanController::class, 'forceDestroy']);

            Route::get('/content/{id}/data', [ManajemenPelatihanController::class, 'getContentData']);
            Route::post('/content/{id}/sub-materi', [ManajemenPelatihanController::class, 'storeSubMateri']);
            Route::put('/sub-materi/{id}', [ManajemenPelatihanController::class, 'updateSubMateri']);
            Route::delete('/sub-materi/{id}', [ManajemenPelatihanController::class, 'destroySubMateri']);

            Route::post('/content/{id}/quiz', [ManajemenPelatihanController::class, 'storePostTest']);
            Route::put('/post-test/{id}', [ManajemenPelatihanController::class, 'updatePostTest']);
            Route::delete('/post-test/{id}', [ManajemenPelatihanController::class, 'destroyPostTest']);

            Route::put('/content/{id}/reorder', [ManajemenPelatihanController::class, 'reorderContent']);
        });
    });

});
