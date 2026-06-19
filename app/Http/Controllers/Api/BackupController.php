<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use App\Models\BackupSetting;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * Menampilkan halaman utama cadangan (Blade)
     */
    public function index()
    {
        // View sekarang tidak membawa data compact lagi karena diambil via API
        return view('SuperAdmin_Views.cadangan');
    }

    /**
     * API: Mengambil seluruh data statistik dan log untuk dikonsumsi JavaScript
     */
    public function getBackupData(Request $request)
    {
        $search = $request->query('search');
        
        $logs = BackupLog::when($search, function($query) use ($search) {
                $query->where('filename', 'like', "%{$search}%")
                      ->orWhere('created_at', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($log) {
                return [
                    'id'         => $log->id,
                    'filename'   => $log->filename,
                    'status'     => $log->status,
                    'size'       => $log->size,
                    'message'    => $log->message,
                    'date'       => $log->created_at->translatedFormat('d M Y, H:i'),
                    'time_ago'   => $log->created_at->diffForHumans(),
                ];
            });

        $settings = BackupSetting::first();
        
        // Disk Space Calculation (menggunakan disk backup di MinIO)
        try {
            $backupDisk = Storage::disk(config('filesystems.default', 'local'));
            $backupName = config('backup.backup.name');
            $totalSize = collect($backupDisk->allFiles($backupName))
                ->sum(fn($file) => $backupDisk->size($file));
            $freeSpaceFormatted = $this->formatBytes($totalSize) . ' terpakai';
        } catch (\Exception $e) {
            $freeSpaceFormatted = 'N/A';
        }

        return response()->json([
            'logs'     => $logs,
            'settings' => $settings,
            'stats'    => [
                'total'      => BackupLog::count(),
                'success'    => BackupLog::where('status', 'success')->count(),
                'failed'     => BackupLog::where('status', 'failed')->count(),
                'free_space' => $freeSpaceFormatted
            ]
        ]);
    }

    /**
     * API: Menjalankan backup di background
     */
    public function runBackup(Request $request)
    {
        try {
            $type = $request->input('type', 'full');
            $phpBinary = PHP_BINARY;
            $artisan = base_path('artisan');

            // Jalankan backup:database di background
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = "start /B \"\" \"{$phpBinary}\" \"{$artisan}\" backup:database --type={$type} 2>&1";
            } else {
                $command = "{$phpBinary} {$artisan} backup:database --type={$type} > /dev/null 2>&1 &";
            }
            pclose(popen($command, 'r'));

            // Catat ke Log Aktivitas
            $this->logActivity($request, 'Create', 'backup_logs', null, 'Memicu proses pencadangan manual (tipe: ' . $type . ')');

            return response()->json([
                'status'  => 'success',
                'message' => 'Proses pencadangan sedang berjalan di latar belakang.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memulai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update pengaturan jadwal backup
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'is_active'      => 'required|boolean',
            'frequency'      => 'required|string|in:daily,weekly,monthly,yearly',
            'execution_time' => 'required',
        ]);

        $settings = BackupSetting::first();
        $settings->update($request->only(['is_active', 'frequency', 'execution_time']));

        $this->logActivity($request, 'Update', 'backup_settings', $settings->id, "Mengubah jadwal menjadi " . ucfirst($request->frequency));

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengaturan berhasil diperbarui'
        ]);
    }

    /**
     * API: Hapus log dan file fisik yang dipilih
     */
    public function deleteSelected(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) return response()->json(['message' => 'Pilih data terlebih dahulu'], 400);

        $logs = BackupLog::whereIn('id', $ids)->get();

        foreach ($logs as $log) {
            if ($log->filename) {
                Storage::disk(config('filesystems.default', 'local'))->delete($log->filename);
            }
            $log->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => count($ids) . ' riwayat berhasil dihapus.'
        ]);
    }

    /**
     * API: Reset semua data backup
     */
    public function reset()
    {
        try {
            $backupName = config('backup.backup.name');
            Storage::disk(config('filesystems.default', 'local'))->deleteDirectory($backupName);
            BackupLog::truncate();

            return response()->json([
                'status'  => 'success',
                'message' => 'Seluruh data cadangan telah dibersihkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper: Format ukuran file
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Helper: Pencatatan log aktivitas seragam
     */
    private function logActivity($request, $tipe, $tabel, $id, $pesan)
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