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
Route::post('/logout', [AuthController::class, 'logoutApi'])->middleware('auth:sanctum');
// Route::post('/logout', [AuthController::class, 'logoutApi'])->middleware('auth');
Route::get('/check-auth', function (Request $request) {
    if (Auth::check()) {
        return response()->json([
            'success' => true,
            'message' => 'User sudah ter-login',
            'data' => [
                'user' => $request->user()
                // 'user' => Auth::user()
            ]
        ], 200);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'User belum login',
        'data' => null
    ], 401);
});

// Test endpoints (no auth for testing)
Route::get('/jenis-tenaga', [JenisTenagaController::class, 'index']);
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

//Pembelajaran API routes
Route::middleware('auth:sanctum')->get('/profile', [PembelajaranController::class, 'getProfile']);
Route::middleware('auth:sanctum')->get('/materi-user', [MateriUserController::class, 'index']);
//detai-materi API route
Route::middleware('auth:sanctum')->get('/materi-user/{id}', [MateriUserController::class, 'show']);
//lanjutkan-materi API route
Route::middleware('auth:sanctum')->get('/materi-lanjutkan/{id}', [MateriUserController::class, 'lanjutkan']);
//post test API route
// Route::middleware('auth:sanctum')->get('/post-test/{materiId}', [MateriUserController::class, 'getPostTest']);
Route::middleware('auth:sanctum')->get('/post-test-soal/{materiId}', [MateriUserController::class, 'getSoalPostTest']);
//submit post test API route
Route::middleware('auth:sanctum')->post('/post-test-submit', [MateriUserController::class, 'submitPostTest']);
//update post test API route
Route::middleware('auth:sanctum')->post('/post-test-start', [MateriUserController::class, 'startPostTest']);
//update progress API route
Route::middleware('auth:sanctum')->post('/progress/update', [MateriUserController::class, 'updateProgress']);


//Notification API routes
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::get('/notifications/count', [NotificationController::class, 'countUnread']);

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

});

// Protected API routes (require authentication)
Route::middleware('auth')->group(function () {
    
    // Jenis Tenaga API
    Route::post('/jenis-tenaga', [JenisTenagaController::class, 'store']);
    Route::put('/jenis-tenaga/{jenisTenaga}', [JenisTenagaController::class, 'update']);
    Route::delete('/jenis-tenaga/{jenisTenaga}', [JenisTenagaController::class, 'destroy']);
    
});
