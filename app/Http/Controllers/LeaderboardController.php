<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use App\Exports\LeaderboardExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $leaderboard = $this->getLeaderboardData($request);

        if ($request->query('all') === 'true') {
            $data = $leaderboard->get();
        } else {
            $data = $leaderboard->paginate($request->query('per_page', 10))->appends($request->query());
        }

        return view('SuperAdmin_Views.detail-leaderboard', [
            'leaderboard' => $data
        ]);
    }

    public function exportPdf(Request $request)
    {
        try {
            $leaderboard = $this->getLeaderboardData($request)->get();
            
            // Activity Log as per specification
            $this->logActivity($request, 'Download', 'users', null, 'Melakukan ekspor data leaderboard');

            $pdf = Pdf::loadView('exports.leaderboard_pdf', [
                'leaderboard' => $leaderboard,
                'date' => now()->translatedFormat('d F Y')
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Leaderboard-LMS-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $leaderboard = $this->getLeaderboardData($request)->get();
            
            // Activity Log as per specification
            $this->logActivity($request, 'Download', 'users', null, 'Melakukan ekspor data leaderboard');

            return Excel::download(new LeaderboardExport($leaderboard), 'Leaderboard-LMS-' . now()->format('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }

    private function getLeaderboardData(Request $request)
    {
        $statusFilter = $request->query('status');
        
        $query = User::with('unitKerja')
            ->withCount(['progresses as pelatihan_selesai' => function ($query) {
                $query->where('status', 'Selesai');
            }]);

        if ($statusFilter == 'terpenuhi') {
            $query->where('total_jpl', '>=', 20);
        } elseif ($statusFilter == 'belum_terpenuhi') {
            $query->where('total_jpl', '<', 20);
        }

        return $query->orderBy('total_jpl', 'desc');
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
            // Silently fail if log creation fails
            \Log::error('Activity Log Failed: ' . $e->getMessage());
        }
    }
}
