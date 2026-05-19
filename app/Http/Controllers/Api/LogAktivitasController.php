<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    /**
     * Mengambil data log dengan filter untuk ditampilkan di tabel (API).
     */
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->latest();

        // Global Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tabel', 'like', "%{$search}%")
                  ->orWhere('perubahan', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Single Date Filter
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Tipe Action Filter
        if ($request->filled('tipe')) {
            $tipe = is_array($request->tipe) ? $request->tipe : explode(',', $request->tipe);
            $query->whereIn('tipe', $tipe);
        }

        $logs = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data log aktivitas',
            'data' => $logs
        ]);
    }

    /**
     * Proses ekspor tetap diletakkan di sini, namun rutenya dipanggil secara eksplisit.
     */
    public function export(Request $request)
    {
        $query = LogAktivitas::with('user')->latest();

        // Apply filters (logika sama dengan index)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tabel', 'like', "%{$search}%")
                  ->orWhere('perubahan', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('tipe')) {
            $tipe = is_array($request->tipe) ? $request->tipe : explode(',', $request->tipe);
            $query->whereIn('tipe', $tipe);
        }

        $logs = $query->get();

        // Recording the export action
        $appliedFilters = [];
        if ($request->filled('search')) $appliedFilters[] = 'kata kunci: "' . $request->search . '"';
        if ($request->filled('tanggal')) $appliedFilters[] = 'tanggal: ' . $request->tanggal;
        
        $detailLog = 'Melakukan ekspor data log aktivitas';
        if (!empty($appliedFilters)) $detailLog .= ' (' . implode(', ', $appliedFilters) . ')';

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'tipe' => 'Download',
            'tabel' => 'Log Aktivitas',
            'subject_id' => null,
            'ip_address' => $request->ip(),
            'perubahan' => $detailLog
        ]);

        // Excel Generation Logic
        $fileName = 'log_aktivitas_' . date('Ymd_His') . '.xlsx';
        $excelData = [['Tanggal & Waktu', 'Nama Pengguna', 'Tipe', 'Tabel', 'IP Address', 'Detail']];

        foreach ($logs as $log) {
            $excelData[] = [
                $log->created_at->format('Y-m-d H:i:s'),
                $log->user->nama ?? 'System',
                $log->tipe,
                $log->tabel,
                $log->ip_address,
                $log->perubahan
            ];
        }

        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($excelData);
        $tempPath = sys_get_temp_dir() . '/' . $fileName;
        $xlsx->saveAs($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}