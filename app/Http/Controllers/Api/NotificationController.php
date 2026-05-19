<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Ambil semua notifikasi user login
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = ($user->role_id == 1);

        $notifications = Notification::where('user_id', $user->user_id)
            ->where('notif_admin', $isAdmin)
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'total_all' => $notifications->count()
        ]);
    }

    /**
     * Ambil hanya notifikasi belum dibaca
     */
    public function unread()
    {
        $user = Auth::user();
        $isAdmin = ($user->role_id == 1);

        $notifications = Notification::where('user_id', $user->user_id)
            ->where('notif_admin', $isAdmin)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAll = Notification::where('user_id', $user->user_id)
            ->where('notif_admin', $isAdmin)
            ->count();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'total_all' => $totalAll
        ]);
    }

    /**
     * Hitung jumlah unread (buat badge)
     */
    public function countUnread()
    {
        $user = Auth::user();
        $isAdmin = ($user->role_id == 1);

        $count = Notification::where('user_id', $user->user_id)
            ->where('notif_admin', $isAdmin)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'total_unread' => $count
        ]);
    }

    /**
     * Tandai 1 notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $isAdmin = ($user->role_id == 1);

        $notification = Notification::where('id', $id)
            ->where('user_id', $user->user_id)
            ->where('notif_admin', $isAdmin)
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notification->is_read = true;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca'
        ]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $isAdmin = ($user->role_id == 1);

        Notification::where('user_id', $user->user_id)
            ->where('notif_admin', $isAdmin)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca'
        ]);
    }

    /**
     * Hapus notifikasi (opsional)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $isAdmin = ($user->role_id == 1);

        $notification = Notification::where('id', $id)
            ->where('user_id', $user->user_id)
            ->where('notif_admin', $isAdmin)
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }
}
