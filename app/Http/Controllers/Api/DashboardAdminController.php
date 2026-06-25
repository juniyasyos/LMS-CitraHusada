<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\JenisTenaga;
use App\Models\Materi;
use App\Models\UserProgress;
use App\Models\LogAktivitas;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelatihanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        try {
            $totalPengguna = User::count();
            $totalUnitKerja = UnitKerja::count();
            $totalJenisTenaga = JenisTenaga::count();
            $totalPelatihan = Materi::count();

            $today = Carbon::today();
            $pelatihanAktif = Materi::whereDate('tanggal_selesai', '>=', $today)->count();
            $pelatihanSelesai = Materi::whereDate('tanggal_selesai', '<', $today)->count();

            return response()->json([
                'success' => true,
                'message' => 'Data statistik dashboard berhasil diambil',
                'data' => [
                    'statistik_utama' => [
                        'total_pengguna' => $totalPengguna,
                        'total_unit_kerja' => $totalUnitKerja,
                        'total_jenis_tenaga' => $totalJenisTenaga,
                        'total_pelatihan' => $totalPelatihan,
                    ],
                    'status_pelatihan' => [
                        'aktif' => $pelatihanAktif,
                        'selesai' => $pelatihanSelesai,
                    ],
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getChartData()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. Grafik Keaktifan (6 bulan terakhir)
        $grafikKeaktifan = [];
        $totalUsers = User::count();

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthNum = $monthDate->month;
            $yearNum = $monthDate->year;
            $monthName = $monthDate->format('M');

            $selesai = UserProgress::where('status', 'Selesai')
                ->whereMonth('updated_at', $monthNum)
                ->whereYear('updated_at', $yearNum)
                ->distinct('user_id')
                ->count('user_id');

            $grafikKeaktifan[] = [
                'month' => $monthName,
                'done' => $selesai,
                'belum_selesai' => max(0, $totalUsers - $selesai)
            ];
        }

        // 2. Grafik Leaderboard Jam Pelajaran
        $colors = ['#3b82f6', '#10b981', '#f43f5e', '#f59e0b', '#8b5cf6'];
        $leaderboardData = UnitKerja::withCount([
            'users' => function ($query) {
                $query->where('total_jpl', '>=', 20);
            }
        ])
            ->having('users_count', '>', 0)
            ->orderBy('users_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($unit, $index) use ($colors) {
                return [
                    'label' => $unit->unit_name,
                    'val' => $unit->users_count,
                    'color' => $colors[$index % count($colors)]
                ];
            });

        if ($leaderboardData->isEmpty()) {
            $leaderboardData = collect([
                [
                    'label' => 'Belum Ada Data',
                    'val' => 1,
                    'color' => '#e5e7eb'
                ]
            ]);
        }

        $totalLeaderboard = $leaderboardData->sum('val');

        return response()->json([
            'grafik_keaktifan' => $grafikKeaktifan,
            'leaderboard' => $leaderboardData,
            'total_leaderboard' => $totalLeaderboard
        ]);
    }

    public function getKaryawanProgress(Request $request)
    {
        try {
            $search = $request->input('search');
            $perPage = $request->input('per_page', 10);

            $query = User::with('unitKerjas')
                ->withCount([
                    'progresses' => function ($query) {
                        $query->where('status', 'Selesai');
                    }
                ])
                ->addSelect('users.*')
                ->selectRaw('COALESCE(users.total_jpl, 0) + COALESCE((
                    SELECT SUM(jpl) 
                    FROM sertifikat_eksternals 
                    WHERE sertifikat_eksternals.user_id = users.user_id AND sertifikat_eksternals.status = "Disetujui"
                ), 0) as total_jpl')
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('nip', 'like', "%{$search}%");
                    });
                })
                ->orderBy('total_jpl', 'desc');

            if ($request->input('all') === 'true') {
                return response()->json([
                    'data' => $query->get()
                ], 200);
            }

            $data = $query->paginate($perPage);

            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data progress karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $leaderboard = $this->applyFilters($request)->get();

            $this->logActivity($request, 'Download', 'users', null, 'Melakukan ekspor data pelatihan ke Excel');

            return Excel::download(new PelatihanExport($leaderboard), 'Statistik-Pelatihan-' . now()->format('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }

    /**
     * Private Helper: Logic filter yang digunakan bersama
     */
    private function applyFilters(Request $request)
    {
        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $query = User::with('unitKerjas')
            ->withCount([
                'progresses as pelatihan_selesai' => function ($query) {
                    $query->where('status', 'Selesai');
                }
            ])
            ->select('users.*')
            ->selectRaw('COALESCE(users.total_jpl, 0) + COALESCE((
                SELECT SUM(jpl) 
                FROM sertifikat_eksternals 
                WHERE sertifikat_eksternals.user_id = users.user_id AND sertifikat_eksternals.status = "Disetujui"
            ), 0) as total_jpl');

        // Filter Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('nip', 'LIKE', "%{$search}%");
            });
        }

        // Filter Status JPL
        if ($statusFilter == 'terpenuhi') {
            $query->having('total_jpl', '>=', 20);
        } elseif ($statusFilter == 'belum_terpenuhi') {
            $query->having('total_jpl', '<', 20);
        }

        return $query->orderBy('total_jpl', 'desc');
    }

    public function exportPdf(Request $request)
    {
        try {
            $users = $this->applyFilters($request)->get();

            $this->logActivity($request, 'Download', 'users', null, 'Melakukan ekspor data pelatihan ke PDF');

            $pdf = Pdf::loadView('exports.pelatihan_pdf', [
                'users' => $users,
                'date' => now()->translatedFormat('d F Y')
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Statistik-Pelatihan-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    private function logActivity(Request $request, $type, $table, $subjectId, $description)
    {
        try {
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'tipe' => $type,
                'tabel' => $table,
                'subject_id' => $subjectId,
                'perubahan' => $description,
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Activity Log Failed: ' . $e->getMessage());
        }
    }
}
