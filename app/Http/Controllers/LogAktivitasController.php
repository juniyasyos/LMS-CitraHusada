<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
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
        if ($request->filled('tipe') && is_array($request->tipe)) {
            $query->whereIn('tipe', $request->tipe);
        }

        $logs = $query->paginate(10)->withQueryString();

        return view('SuperAdmin_Views.log-aktivitas', compact('logs'));
    }

    public function export(Request $request)
    {
        $query = LogAktivitas::with('user')->latest();

        // Apply same filters as index
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
        if ($request->filled('tipe') && is_array($request->tipe)) {
            $query->whereIn('tipe', $request->tipe);
        }

        $logs = $query->get();

        // Log the export activity with detail
        $perubahanDetail = 'Melakukan ekspor data log aktivitas';
        $appliedFilters = [];
        if ($request->filled('search')) $appliedFilters[] = 'kata kunci: "' . $request->search . '"';
        if ($request->filled('tanggal')) $appliedFilters[] = 'tanggal: ' . $request->tanggal;
        if ($request->filled('tipe') && is_array($request->tipe)) $appliedFilters[] = 'tipe: ' . implode(', ', $request->tipe);
        
        if (!empty($appliedFilters)) {
            $perubahanDetail .= ' (' . implode(', ', $appliedFilters) . ')';
        }

        LogAktivitas::create([
            'user_id' => auth()->id() ?? 1, // Fallback if no session for testing
            'tipe' => 'Download',
            'tabel' => 'Log Aktivitas',
            'subject_id' => null,
            'ip_address' => $request->ip(),
            'perubahan' => $perubahanDetail
        ]);

        $fileName = 'log_aktivitas_' . date('Ymd_His') . '.xlsx';

        $excelData = [
            ['Tanggal & Waktu', 'Nama Pengguna', 'Tipe', 'Tabel', 'IP Address', 'Detail']
        ];

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
