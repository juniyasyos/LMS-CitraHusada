<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\JenisTenagaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembelajaranController;
use App\Http\Controllers\MateriUserController;
use App\Http\Controllers\NotificationController;

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

Route::get('/jenis-tenaga', [JenisTenagaController::class, 'index']); // Misal tenaga publik
Route::get('/jenis-tenaga/{jenisTenaga}', [JenisTenagaController::class, 'show']);

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
    Route::middleware('role:4')->group(function () {
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
    });

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
    Route::middleware('role:1')->prefix('admin')->group(function () {
        // Dashboard Statistik
        Route::get('/dashboard/charts', [\App\Http\Controllers\DashboardSuperadminController::class, 'getChartData'])->name('api.dashboard.charts');

        // Manajemen Users
        Route::get('/manajemen-pengguna', [\App\Http\Controllers\ManajemenPenggunaController::class, 'getData'])->name('api.manajemen-pengguna');
        
        // Master Data Jenis Tenaga
        Route::post('/jenis-tenaga', [JenisTenagaController::class, 'store']);
        Route::put('/jenis-tenaga/{jenisTenaga}', [JenisTenagaController::class, 'update']);
        Route::delete('/jenis-tenaga/{jenisTenaga}', [JenisTenagaController::class, 'destroy']);
    });
});
