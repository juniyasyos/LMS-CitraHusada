<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestoreBackupRequest;
use App\Models\LogAktivitas;
use App\Models\RestoreLog;
use App\Services\RestoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RestoreController extends Controller
{
    protected RestoreService $restoreService;

    public function __construct(RestoreService $restoreService)
    {
        $this->restoreService = $restoreService;
    }

    /**
     * API: Menampilkan daftar file backup yang tersedia untuk restore.
     */
    public function getBackupFiles(): JsonResponse
    {
        try {
            $backupName = config('backup.backup.name', 'Laravel');
            $disk = Storage::disk(config('filesystems.default', 'local'));

            $files = collect($disk->allFiles($backupName))
                ->filter(fn($file) => str_ends_with($file, '.zip'))
                ->map(function ($file) use ($disk) {
                    $sizeBytes = $disk->size($file);
                    $lastModified = $disk->lastModified($file);

                    return [
                        'path'          => $file,
                        'filename'      => basename($file),
                        'size_bytes'    => $sizeBytes,
                        'size_formatted' => $this->formatBytes($sizeBytes),
                        'date'          => date('d M Y, H:i', $lastModified),
                        'date_raw'      => $lastModified,
                        'time_ago'      => \Carbon\Carbon::createFromTimestamp($lastModified)->diffForHumans(),
                    ];
                })
                ->sortByDesc('date_raw')
                ->values();

            return response()->json([
                'status' => 'success',
                'data'   => $files,
            ]);
        } catch (\Exception $e) {
            Log::error('[Restore] Gagal mengambil daftar backup: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengambil daftar file backup: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Download file backup.
     */
    public function downloadBackup(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
        ]);

        $file = $request->query('file');
        $disk = Storage::disk(config('filesystems.default', 'local'));

        if (!$disk->exists($file)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'File backup tidak ditemukan.',
            ], 404);
        }

        $this->logActivity($request, 'Download', 'backup_logs', null, 'Mengunduh file backup: ' . basename($file));

        return $disk->download($file, basename($file));
    }

    /**
     * API: Menjalankan proses restore dari file backup yang dipilih.
     */
    public function restore(RestoreBackupRequest $request): JsonResponse
    {
        $backupFile = $request->validated()['backup_file'];
        $userId = auth()->id();

        try {
            Log::info('[Restore] User #' . $userId . ' memulai restore dari file: ' . $backupFile);

            $this->logActivity($request, 'Restore', 'restore_logs', null, 'Memulai proses restore dari backup: ' . basename($backupFile));

            $restoreLog = $this->restoreService->restore($backupFile, $userId);

            if ($restoreLog->status === 'success') {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Restore berhasil diselesaikan. Database dan storage telah dikembalikan ke kondisi backup.',
                    'data'    => [
                        'restore_log_id' => $restoreLog->id,
                        'started_at'     => $restoreLog->restore_started_at->format('d M Y, H:i:s'),
                        'finished_at'    => $restoreLog->restore_finished_at->format('d M Y, H:i:s'),
                    ],
                ]);
            }

            // Status rolled_back atau failed
            return response()->json([
                'status'  => 'error',
                'message' => $restoreLog->message,
                'data'    => [
                    'restore_log_id' => $restoreLog->id,
                    'final_status'   => $restoreLog->status,
                ],
            ], 500);

        } catch (\Exception $e) {
            Log::error('[Restore] Error tidak terduga: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi error tidak terduga saat proses restore: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Menampilkan log riwayat restore.
     */
    public function getRestoreLogs(Request $request): JsonResponse
    {
        $search = $request->query('search');

        $logs = RestoreLog::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('backup_file', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id'                 => $log->id,
                    'backup_file'        => basename($log->backup_file),
                    'backup_file_full'   => $log->backup_file,
                    'restored_by'        => $log->user ? $log->user->nama : 'Unknown',
                    'restore_started_at' => $log->restore_started_at ? $log->restore_started_at->format('d M Y, H:i:s') : '-',
                    'restore_finished_at'=> $log->restore_finished_at ? $log->restore_finished_at->format('d M Y, H:i:s') : '-',
                    'duration'           => $this->calculateDuration($log->restore_started_at, $log->restore_finished_at),
                    'status'             => $log->status,
                    'message'            => $log->message,
                    'pre_restore_backup' => $log->pre_restore_backup ? basename($log->pre_restore_backup) : '-',
                    'date'               => $log->created_at->translatedFormat('d M Y, H:i'),
                ];
            });

        return response()->json([
            'status' => 'success',
            'data'   => $logs,
            'stats'  => [
                'total'       => RestoreLog::count(),
                'success'     => RestoreLog::where('status', 'success')->count(),
                'failed'      => RestoreLog::where('status', 'failed')->count(),
                'rolled_back' => RestoreLog::where('status', 'rolled_back')->count(),
            ],
        ]);
    }

    /**
     * Helper: Menghitung durasi antara dua waktu.
     */
    private function calculateDuration($start, $end): string
    {
        if (!$start || !$end) return '-';

        $diff = $start->diff($end);

        if ($diff->h > 0) {
            return $diff->format('%h jam %i menit %s detik');
        }
        if ($diff->i > 0) {
            return $diff->format('%i menit %s detik');
        }
        return $diff->format('%s detik');
    }

    /**
     * Helper: Format ukuran file.
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Helper: Pencatatan log aktivitas seragam (konsisten dengan BackupController).
     */
    private function logActivity($request, $tipe, $tabel, $id, $pesan): void
    {
        try {
            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'tipe'       => $tipe,
                'tabel'      => $tabel,
                'subject_id' => $id,
                'perubahan'  => $pesan,
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) { /* silent */ }
    }
}
