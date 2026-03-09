<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriUserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $materis = Materi::query()

            // SEARCH
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('subjudul', 'like', '%' . $search . '%');
                });
            })

            // FILTER BERDASARKAN JENIS TENAGA / UNIT
            ->where(function ($query) use ($user) {

                $query->whereHas('materiJenisTenagas', function ($q) use ($user) {
                    $q->where('jenis_tenaga_id', $user->jenis_tenaga_id);
                })
                ->orWhereHas('materiUnitKerjas', function ($q) use ($user) {
                    $q->where('unit_kerja_id', $user->unit_kerja_id);
                });

            })

            ->with([
                'subMateris',
                'postTests',
                'progresses' => function ($q) use ($user) {
                    $q->where('user_id', $user->user_id);
                }
            ])

            ->orderBy('tanggal_upload', 'desc')
            ->get();


        $materis = $materis->map(function ($materi) {

            $totalSub = $materi->subMateris->count();
            $totalTest = $materi->postTests->count();

            $totalStep = $totalSub + $totalTest;

            $progress = $materi->progresses->first();

            $urutan = $progress->urutan_selesai ?? 0;

            $progressPercent = $totalStep > 0
                ? round(($urutan / $totalStep) * 100)
                : 0;

            $status = $progress->status ?? 'Belum Dimulai';

            return [
                'materi_id' => $materi->materi_id,
                'judul' => $materi->judul,
                'subjudul' => $materi->subjudul,
                'image' => $materi->image_path,
                'jam_pelajaran' => $materi->jam_pelajaran,
                'tanggal_selesai' => $materi->tanggal_selesai,
                'progress_percent' => $progressPercent,
                'status' => $status
            ];
        });


        // FILTER STATUS
        if ($statusFilter) {

            if ($statusFilter == 'belum') {
                $materis = $materis->where('status', 'Belum Dimulai');
            }

            if ($statusFilter == 'progres') {
                $materis = $materis->where('status', 'Progres');
            }

            if ($statusFilter == 'selesai') {
                $materis = $materis->where('status', 'Selesai');
            }

            $materis = $materis->values();
        }

        return response()->json([
            'data' => $materis
        ]);
    }
}