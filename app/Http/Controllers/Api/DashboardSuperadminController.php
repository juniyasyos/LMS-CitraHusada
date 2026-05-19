<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\JenisTenaga;
use App\Models\Materi;
use App\Models\UserProgress;
use App\Models\LogAktivitas;
use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;

class DashboardSuperadminController extends Controller
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

            // Ambil log aktivitas terbaru (misal batasi 10 agar response tidak terlalu berat)
            $logAktivitas = LogAktivitas::with('user:user_id,nama') // Hanya ambil kolom yang perlu
                ->latest()
                ->take(3)
                ->get();

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
                    'log_aktivitas' => $logAktivitas,
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

    // Method getChartData tetap menggunakan format JSON (sudah benar dari kode awal kamu)
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
                    'label' => $unit->unit_kerja,
                    'val' => $unit->users_count,
                    'color' => $colors[$index % count($colors)]
                ];
            });

        // Add dummy data if zero to prevent empty chart
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
}