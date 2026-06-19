<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\Sertifikat;
use App\Models\SkorUser;
use App\Models\UnitKerja;
use App\Models\SertifikatEksternal;
use App\Models\LogAktivitas;
use App\Exports\MonitoringExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaporanMonitoringController extends Controller
{
    /**
     * Menampilkan halaman Blade (Statik)
     */
    public function index()
    {
        $unitKerjas = UnitKerja::all();
        if (auth()->user()->role_id == 2) {
            return view('Admin_Views.laporan-monitoring-admin', compact('unitKerjas'));
        }
        return view('SuperAdmin_Views.laporan-monitoring', compact('unitKerjas'));
    }

    /**
     * API: Mengambil data Sertifikat Eksternal (dikelompokkan per user)
     */
    public function getSertifikatEksternalData(Request $request)
    {
        $query = User::select('users.user_id', 'users.nama', 'users.nik', 'users.unit_kerja_id')
            ->join('sertifikat_eksternals', 'users.user_id', '=', 'sertifikat_eksternals.user_id')
            ->with('unitKerja')
            ->groupBy('users.user_id', 'users.nama', 'users.nik', 'users.unit_kerja_id')
            ->selectRaw('COUNT(sertifikat_eksternals.sertifikat_eksternal_id) as jumlah_sertifikat')
            ->selectRaw("SUM(CASE WHEN sertifikat_eksternals.status = 'Belum Disetujui' THEN 1 ELSE 0 END) as jumlah_belum_disetujui");

        // Filter berdasarkan unit kerja
        if ($unitFilter = $request->input('unit_kerja')) {
            $query->where('users.unit_kerja_id', $unitFilter);
        }

        // Filter berdasarkan rentang waktu
        if ($startDate = $request->input('start_date')) {
            $endDate = $request->input('end_date') ?: $startDate;
            $query->whereBetween('sertifikat_eksternals.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $data = $query->orderBy('users.nama')->paginate(10);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * API: Mengambil list data Sertifikat Eksternal secara detail (tanpa grouping)
     */
    public function getSertifikatEksternalList(Request $request)
    {
        $query = SertifikatEksternal::with(['user.unitKerja']);

        // Filter berdasarkan unit kerja
        if ($unitFilter = $request->input('unit_kerja')) {
            $query->whereHas('user', function($q) use ($unitFilter) {
                $q->where('unit_kerja_id', $unitFilter);
            });
        }

        // Filter berdasarkan rentang waktu
        if ($startDate = $request->input('start_date')) {
            $endDate = $request->input('end_date') ?: $startDate;
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        // Filter berdasarkan pencarian nama atau judul
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('nama', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        $data = $query->orderByDesc('created_at')->paginate(10);

        // Map data sesuai format yang diminta
        $mappedData = $data->getCollection()->map(function($item) {
            return [
                'sertifikat_eksternal_id' => $item->sertifikat_eksternal_id,
                'user_id' => $item->user_id,
                'nama' => $item->user->nama ?? '-',
                'nik' => $item->user->nik ?? '-',
                'unit_kerja' => $item->user->unitKerja->unit_kerja ?? '-',
                'judul' => $item->judul,
                'status' => $item->status,
                'image_path' => $item->image_path
            ];
        });

        // Set collection kembali
        $data->setCollection($mappedData);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Menampilkan halaman review Pelatihan Eksternal
     */
    public function showReviewPelatihan($sertifikatEksternalId)
    {
        $sertifikat = SertifikatEksternal::with('user')->findOrFail($sertifikatEksternalId);
        $user = $sertifikat->user;

        // Internal JPL: total_jpl pada tabel users
        $internalJpl = $user->total_jpl ?? 0;

        // Eksternal JPL: jumlah semua jpl sertifikat_eksternal yang disetujui
        $eksternalJpl = SertifikatEksternal::where('user_id', $user->user_id)
            ->where('status', 'Disetujui')
            ->sum('jpl');

        // Total Keseluruhan
        $totalJpl = $internalJpl + $eksternalJpl;

        // Generate PDF URL (mendukung Temporary URL jika disk default adalah S3/MinIO dan bucket private)
        $pdfUrl = '';
        if ($sertifikat->image_path) {
            try {
                if (config('filesystems.default') === 's3') {
                    $pdfUrl = Storage::temporaryUrl($sertifikat->image_path, now()->addMinutes(30));
                } else {
                    $pdfUrl = Storage::url($sertifikat->image_path);
                }
            } catch (\Exception $e) {
                $pdfUrl = Storage::url($sertifikat->image_path);
            }
        }

        return view('Admin_Views.review-pelatihan', compact('sertifikat', 'user', 'internalJpl', 'eksternalJpl', 'totalJpl', 'pdfUrl'));
    }

    /**
     * API / Web POST Action: Melakukan verifikasi kelayakan sertifikat eksternal
     */
    public function verifikasiSertifikatEksternal(Request $request, $sertifikatEksternalId)
    {
        $sertifikat = SertifikatEksternal::findOrFail($sertifikatEksternalId);
        $decision = $request->input('decision'); // 'Setuju' atau 'Tolak'

        if ($decision === 'Setuju') {
            $request->validate([
                'jpl' => 'required|numeric|min:1'
            ], [
                'jpl.required' => 'Konfirmasi Jam Pembelajaran (JPL) wajib diisi untuk menyetujui sertifikat.',
                'jpl.numeric' => 'JPL harus berupa angka.'
            ]);

            $sertifikat->jpl = $request->input('jpl');
            $sertifikat->deskripsi = $request->input('deskripsi'); // opsional
            $sertifikat->status = 'Disetujui';
        } else {
            $request->validate([
                'deskripsi' => 'required|string|min:5'
            ], [
                'deskripsi.required' => 'Komentar/Catatan Peninjauan wajib diisi sebagai alasan penolakan sertifikat.'
            ]);

            $sertifikat->jpl = 0;
            $sertifikat->deskripsi = $request->input('deskripsi');
            $sertifikat->status = 'Ditolak';
        }

        $sertifikat->save();

        return response()->json([
            'success' => true,
            'message' => 'Status sertifikat eksternal berhasil diperbarui.'
        ]);
    }

    /**
     * Menampilkan halaman detail Sertifikat Eksternal per User
     */
    public function showSertifikatEksternal($userId)
    {
        $user = User::findOrFail($userId);
        return view('SuperAdmin_Views.sertifikat-eksternal', compact('user'));
    }

    /**
     * API: Mengambil data sertifikat eksternal milik user tertentu
     */
    public function getUserSertifikatEksternal(Request $request, $userId)
    {
        $query = SertifikatEksternal::where('user_id', $userId);

        // Filter berdasarkan rentang waktu
        if ($startDate = $request->input('start_date')) {
            $endDate = $request->input('end_date') ?: $startDate;
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        // Filter berdasarkan status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $data = $query->orderByDesc('created_at')->paginate(10);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Ekspor Excel Sertifikat Eksternal per User
     */
    public function exportSertifikatEksternalExcel(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $this->logActivity($request, 'Download', 'sertifikat_eksternals', auth()->id(), "Mengekspor sertifikat eksternal {$user->nama} (Excel)");
        return Excel::download(new \App\Exports\SertifikatEksternalExport($request, $userId), 'sertifikat-eksternal-' . $user->nama . '-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Ekspor PDF Sertifikat Eksternal per User
     */
    public function exportSertifikatEksternalPdf(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $query = SertifikatEksternal::where('user_id', $userId);

        if ($startDate = $request->input('start_date')) {
            $endDate = $request->input('end_date') ?: $startDate;
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $sertifikats = $query->orderByDesc('created_at')->get();
        $pdf = Pdf::loadView('exports.sertifikat_eksternal_pdf', compact('sertifikats', 'user'))->setPaper('a4', 'landscape');

        $this->logActivity($request, 'Download', 'sertifikat_eksternals', auth()->id(), "Mengekspor sertifikat eksternal {$user->nama} (PDF)");
        return $pdf->download('sertifikat-eksternal-' . $user->nama . '-' . now()->format('Y-m-d') . '.pdf');
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
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $unitFilter = $request->input('unit_kerja');
        $statusFilter = $request->input('status');

        $query = UserProgress::with(['user.unitKerja', 'materi' => function($q) {
            $q->withCount(['subMateris', 'postTests']);
        }]);

        $query->select('user_progress.*');
        $query->addSelect([
            'sertifikat_status' => \App\Models\Sertifikat::select('status')
                ->whereColumn('sertifikats.user_id', 'user_progress.user_id')
                ->whereColumn('sertifikats.materi_id', 'user_progress.materi_id')
                ->limit(1),
            'sertifikat_image_path' => \App\Models\Sertifikat::select('image_path')
                ->whereColumn('sertifikats.user_id', 'user_progress.user_id')
                ->whereColumn('sertifikats.materi_id', 'user_progress.materi_id')
                ->limit(1)
        ]);

        if ($startDate) {
            $endDate = $endDate ?: $startDate;
            $query->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
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
        if ($startDate = $request->input('start_date')) {
            $endDate = $request->input('end_date') ?: $startDate;
            $query->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
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