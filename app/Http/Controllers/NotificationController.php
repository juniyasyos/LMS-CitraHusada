<?php

namespace App\Http\Controllers;

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

        $notifications = Notification::where('user_id', $user->user_id)
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

        $notifications = Notification::where('user_id', $user->user_id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAll = Notification::where('user_id', $user->user_id)->count();

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

        $count = Notification::where('user_id', $user->user_id)
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

        $notification = Notification::where('id', $id)
            ->where('user_id', $user->user_id)
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

        Notification::where('user_id', $user->user_id)
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

        $notification = Notification::where('id', $id)
            ->where('user_id', $user->user_id)
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