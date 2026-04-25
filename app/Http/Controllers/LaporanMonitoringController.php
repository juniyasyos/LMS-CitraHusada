<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\Sertifikat;
use App\Models\SkorUser;
use App\Models\UnitKerja;
use App\Models\Materi;
use App\Models\LogAktivitas;
use App\Exports\MonitoringExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanMonitoringController extends Controller
{
    public function index(Request $request)
    {
        // Statistik Utama - Sertakan semua user (termasuk Superadmin)
        $totalPeserta = User::count();

        $totalSelesai = UserProgress::where('status', 'Selesai')->count();
        $penyelesaianPercent = $totalPeserta > 0 ? round(($totalSelesai / $totalPeserta) * 100) : 0;

        $totalSertifikat = Sertifikat::count();
        $rataRataNilai = round(SkorUser::avg('skor'), 1) ?: 0;

        // Data untuk Tabel Sertifikat Internal
        $search = $request->input('search');
        $unitFilter = $request->input('unit_kerja');
        $statusFilter = $request->input('status');

        $query = UserProgress::with(['user.unitKerja', 'materi']);

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

        $internalReports = $query->latest()->paginate(10)->withQueryString();

        // Data Unit Kerja untuk Filter
        $unitKerjas = UnitKerja::all();

        return view('SuperAdmin_Views.laporan-monitoring', compact(
            'totalPeserta',
            'penyelesaianPercent',
            'totalSertifikat',
            'rataRataNilai',
            'internalReports',
            'unitKerjas'
        ));
    }

    public function exportExcel(Request $request)
    {
        $this->logActivity($request, 'Download', 'users', auth()->id(), "Melakukan ekspor laporan monitoring ke format Excel");

        return Excel::download(new MonitoringExport($request), 'laporan-monitoring-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $unitFilter = $request->input('unit_kerja');
        $statusFilter = $request->input('status');

        $query = UserProgress::with(['user.unitKerja', 'materi']);

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

        $reports = $query->latest()->get();

        $pdf = Pdf::loadView('exports.monitoring_pdf', compact('reports'))
            ->setPaper('a4', 'landscape');

        $this->logActivity($request, 'Download', 'users', auth()->id(), "Melakukan ekspor laporan monitoring ke format PDF");

        return $pdf->download('laporan-monitoring-' . now()->format('Y-m-d') . '.pdf');
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
