<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\JenisTenaga;
use App\Models\Materi;
use App\Models\UserProgress;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardSuperadminController extends Controller
{
    public function index()
    {
        $totalPengguna = User::count();
        $totalUnitKerja = UnitKerja::count();
        $totalJenisTenaga = JenisTenaga::count();
        $totalPelatihan = Materi::count();
        
        $today = Carbon::today();
        $pelatihanAktif = Materi::whereDate('tanggal_selesai', '>=', $today)->count();
        $pelatihanSelesai = Materi::whereDate('tanggal_selesai', '<', $today)->count();

        $logAktivitas = LogAktivitas::with('user')->latest()->get();

        return view('SuperAdmin_Views.beranda-superadmin')->with([
            'totalPengguna' => $totalPengguna,
            'totalUnitKerja' => $totalUnitKerja,
            'totalJenisTenaga' => $totalJenisTenaga,
            'totalPelatihan' => $totalPelatihan,
            'pelatihanAktif' => $pelatihanAktif,
            'pelatihanSelesai' => $pelatihanSelesai,
            'logAktivitas' => $logAktivitas,
        ]);
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
        $leaderboardData = UnitKerja::withCount(['users' => function($query) {
            $query->where('total_jpl', '>=', 20);
        }])
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
             $leaderboardData = collect([[
                'label' => 'Belum Ada Data',
                'val' => 1,
                'color' => '#e5e7eb'
            ]]);
        }
        
        $totalLeaderboard = $leaderboardData->sum('val');

        return response()->json([
            'grafik_keaktifan' => $grafikKeaktifan,
            'leaderboard' => $leaderboardData,
            'total_leaderboard' => $totalLeaderboard
        ]);
    }
}
