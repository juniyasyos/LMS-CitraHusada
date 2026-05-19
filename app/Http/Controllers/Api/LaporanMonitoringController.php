<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\Sertifikat;
use App\Models\SkorUser;
use App\Models\UnitKerja;
use App\Models\LogAktivitas;
use App\Exports\MonitoringExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanMonitoringController extends Controller
{
    /**
     * Menampilkan halaman Blade (Statik)
     */
    public function index()
    {
        $unitKerjas = UnitKerja::all();
        return view('SuperAdmin_Views.laporan-monitoring', compact('unitKerjas'));
    }

    /**
     * API: Mengambil data statistik dan tabel secara asinkron
     */
    public function getMonitoringData(Request $request)
    {
        // 1. Statistik Utama
        $totalPeserta = User::count();
        $totalSelesai = UserProgress::where('status', 'Selesai')->count();
        
        $stats = [
            'total_peserta' => $totalPeserta,
            'penyelesaian_percent' => $totalPeserta > 0 ? round(($totalSelesai / $totalPeserta) * 100) : 0,
            'total_sertifikat' => Sertifikat::count(),
            'rata_rata_nilai' => round(SkorUser::avg('skor'), 1) ?: 0,
        ];

        // 2. Query Tabel dengan Filter
        $search = $request->input('search');
        $unitFilter = $request->input('unit_kerja');
        $statusFilter = $request->input('status');

        $query = UserProgress::with(['user.unitKerja', 'materi' => function($q) {
            $q->withCount(['subMateris', 'postTests']);
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('nik', 'LIKE', "%{$search}%");
                })->orWhereHas('materi', function ($mq) use ($search) {
                    $mq->where('judul', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($unitFilter) {
            $query->whereHas('user', function ($q) use ($unitFilter) {
                $q->where('unit_kerja_id', $unitFilter);
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $reports = $query->latest()->paginate(10);

        return response()->json([
            'stats' => $stats,
            'reports' => $reports
        ]);
    }

    /**
     * Ekspor Excel (Tetap menggunakan logic lama)
     */
    public function exportExcel(Request $request)
    {
        $this->logActivity($request, 'Download', 'users', auth()->id(), "Mengekspor laporan monitoring (Excel)");
        return Excel::download(new MonitoringExport($request), 'laporan-monitoring-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Ekspor PDF (Tetap menggunakan logic lama)
     */
    public function exportPdf(Request $request)
    {
        $query = UserProgress::with(['user.unitKerja', 'materi' => function($q) {
            $q->withCount(['subMateris', 'postTests']);
        }]);

        // ... logic filter yang sama dengan getMonitoringData ...
        $this->applyFilters($query, $request);

        $reports = $query->latest()->get();
        $pdf = Pdf::loadView('exports.monitoring_pdf', compact('reports'))->setPaper('a4', 'landscape');

        $this->logActivity($request, 'Download', 'users', auth()->id(), "Mengekspor laporan monitoring (PDF)");
        return $pdf->download('laporan-monitoring-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Helper: Menyamakan filter untuk API dan Export
     */
    private function applyFilters($query, Request $request)
    {
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('nama', 'LIKE', "%{$search}%")->orWhere('nik', 'LIKE', "%{$search}%");
                })->orWhereHas('materi', function ($mq) use ($search) {
                    $mq->where('judul', 'LIKE', "%{$search}%");
                });
            });
        }
        if ($unit = $request->input('unit_kerja')) {
            $query->whereHas('user', function ($q) use ($unit) { $q->where('unit_kerja_id', $unit); });
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
    }

    private function logActivity(Request $request, $tipe, $tabel, $subjectId, $perubahan)
    {
        try {
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'tipe' => $tipe,
                'tabel' => $tabel,
                'subject_id' => $subjectId,
                'perubahan' => $perubahan,
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            Log::error('Log Aktivitas Gagal: ' . $e->getMessage());
        }
    }
}