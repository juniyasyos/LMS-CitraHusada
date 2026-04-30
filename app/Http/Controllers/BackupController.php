<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use App\Models\BackupSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $logs = BackupLog::when($search, function($query) use ($search) {
                $query->where('filename', 'like', "%{$search}%")
                      ->orWhere('created_at', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $settings = BackupSetting::first();
        
        // Stats
        $totalLogs = BackupLog::count();
        $successCount = BackupLog::where('status', 'success')->count();
        $failedCount = BackupLog::where('status', 'failed')->count();
        
        // Disk Space
        $freeSpaceBytes = disk_free_space(storage_path());
        $freeSpaceFormatted = $this->formatBytes($freeSpaceBytes);

        return view('SuperAdmin_Views.cadangan', compact(
            'logs', 
            'settings', 
            'totalLogs', 
            'successCount', 
            'failedCount', 
            'freeSpaceFormatted'
        ));
    }

    /**
     * Run backup as a detached CLI process.
     * 
     * WHY: mysqldump fails with socket error 10106 when spawned from 
     * Apache's process context on Windows (Winsock provider init failure).
     * Running via `popen` spawns a new PHP CLI process which has full 
     * socket access, bypassing the Apache restriction entirely.
     */
    public function runBackup(Request $request)
    {
        try {
            $phpBinary = PHP_BINARY;
            $artisan = base_path('artisan');

            // Spawn as detached background process on Windows
            $command = "start /B \"\" \"{$phpBinary}\" \"{$artisan}\" backup:run --only-db 2>&1";
            pclose(popen($command, 'r'));

            // Activity Log Implementation
            try {
                \App\Models\LogAktivitas::create([
                    'user_id'    => auth()->id(),
                    'tipe'       => 'Create', 
                    'tabel'      => 'backup_logs',
                    'subject_id' => null, 
                    'perubahan'  => 'Memicu proses pencadangan database manual di latar belakang',
                    'ip_address' => $request->ip(),
                ]);
            } catch (\Exception $e) {
                // Fail silently
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Proses pencadangan sistem sedang berjalan di latar belakang.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memulai pencadangan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
            'frequency' => 'required|string|in:daily,weekly,monthly,yearly',
            'execution_time' => 'required',
        ]);

        $settings = BackupSetting::first();
        $settings->update([
            'is_active' => $request->is_active,
            'frequency' => $request->frequency,
            'execution_time' => $request->execution_time,
        ]);

        // Activity Log Implementation
        try {
            \App\Models\LogAktivitas::create([
                'user_id'    => auth()->id(),
                'tipe'       => 'Update',
                'tabel'      => 'backup_settings',
                'subject_id' => $settings->id,
                'perubahan'  => "Mengubah jadwal cadangan menjadi " . ucfirst($request->frequency),
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // Fail silently
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaturan cadangan diperbarui'
        ]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) return response()->json(['message' => 'Pilih data terlebih dahulu'], 400);

        $logs = BackupLog::whereIn('id', $ids)->get();

        foreach ($logs as $log) {
            if ($log->filename) {
                Storage::disk('local')->delete($log->filename);
            }
            $log->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => count($ids) . ' data berhasil dihapus.'
        ]);
    }

    public function reset()
    {
        try {
            $backupName = config('backup.backup.name');
            Storage::disk('local')->deleteDirectory($backupName);
            
            BackupLog::truncate();

            return response()->json([
                'status' => 'success',
                'message' => 'Seluruh data cadangan dan riwayat berhasil dibersihkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan reset: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
