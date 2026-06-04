<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LogAktivitas;
use App\Exports\LeaderboardExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LeaderboardController extends Controller
{
    /**
     * Menampilkan halaman Blade Utama
     */
    public function index()
    {
        return view('SuperAdmin_Views.detail-leaderboard');
    }

    /**
     * API: Mengambil data leaderboard untuk tabel asinkron
     */
    public function getLeaderboardDataApi(Request $request)
    {
        $query = $this->applyLeaderboardFilters($request);
        
        if ($request->query('all') === 'true') {
            $data = $query->get();
            return response()->json([
                'data' => $data,
                'links' => []
            ]);
        }

        $perPage = $request->query('per_page', 10);
        $leaderboard = $query->paginate($perPage);

        return response()->json($leaderboard);
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $leaderboard = $this->applyLeaderboardFilters($request)->get();
            
            $this->logActivity($request, 'Download', 'users', null, 'Melakukan ekspor data leaderboard ke PDF');

            $pdf = Pdf::loadView('exports.leaderboard_pdf', [
                'leaderboard' => $leaderboard,
                'date' => now()->translatedFormat('d F Y')
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Leaderboard-LMS-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $leaderboard = $this->applyLeaderboardFilters($request)->get();
            
            $this->logActivity($request, 'Download', 'users', null, 'Melakukan ekspor data leaderboard ke Excel');

            return Excel::download(new LeaderboardExport($leaderboard), 'Leaderboard-LMS-' . now()->format('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }

    /**
     * Private Helper: Logic filter yang digunakan bersama
     */
    private function applyLeaderboardFilters(Request $request)
    {
        $statusFilter = $request->query('status');
        $search = $request->query('search');
        
        $query = User::with('unitKerja')
            ->withCount([
                'progresses as pelatihan_selesai' => function ($query) {
                    $query->where('status', 'Selesai');
                }
            ])
            ->select('users.*')
            ->selectRaw('
                COALESCE(users.total_jpl, 0) +
                COALESCE((
                    SELECT SUM(jpl)
                    FROM sertifikat_eksternals
                    WHERE sertifikat_eksternals.user_id = users.user_id
                    AND sertifikat_eksternals.status = "Disetujui"
                ), 0) as total_jpl
            ');

        // Filter Search
        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
        }

        // Filter Status JPL
        if ($statusFilter == 'terpenuhi') {
            $query->having('total_jpl', '>=', 20);
        } elseif ($statusFilter == 'belum_terpenuhi') {
            $query->having('total_jpl', '<', 20);
        }

        return $query->orderBy('total_jpl', 'desc');
    }

    /**
     * Private Helper: Log Aktivitas
     */
    private function logActivity(Request $request, $type, $table, $subjectId, $description)
    {
        try {
            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'tipe'       => $type,
                'tabel'      => $table,
                'subject_id' => $subjectId,
                'perubahan'  => $description,
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Activity Log Failed: ' . $e->getMessage());
        }
    }
}