<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProgress;
use App\Models\SkorUser;

class MateriUserController extends Controller
{
    //pembelajaran
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
//########################################################################################################################################################
    
    //detail materi
    public function show($id)
    {
        $user = Auth::user();

        $materi = Materi::with([
            'subMateris',
            'postTests',
            'progresses' => function ($q) use ($user) {
                $q->where('user_id', $user->user_id);
            }
        ])->findOrFail($id);

        $progress = $materi->progresses->first();
        $urutanSelesai = $progress->urutan_selesai ?? 0;

        $steps = collect();

        // SUB MATERI
        foreach ($materi->subMateris as $sub) {
            $steps->push([
                'type' => 'sub_materi',
                'id' => $sub->sub_materi_id,
                'judul' => $sub->judul,
                'urutan' => $sub->urutan_sub_materi
            ]);
        }

        // POST TEST
        foreach ($materi->postTests as $test) {
            $steps->push([
                'type' => 'post_test',
                'id' => $test->post_test_id,
                'judul' => 'Kuis',
                'urutan' => $test->urutan_post_test
            ]);
        }

        // SORT BERDASARKAN URUTAN
        $steps = $steps->sortBy('urutan')->values();

        return response()->json([
            'data' => [
                'materi_id' => $materi->materi_id,
                'judul' => $materi->judul,
                'deskripsi' => $materi->deskripsi,
                'jam_pelajaran' => $materi->jam_pelajaran,
                'steps' => $steps,
                'urutan_selesai' => $urutanSelesai
            ]
        ]);
    } 

//########################################################################################################################################################
    
    //lanjutkan materi
    public function lanjutkan($id)
    {
        $user = Auth::user();

        $materi = Materi::with([
            'subMateris',
            'postTests',
            'progresses' => function ($q) use ($user) {
                $q->where('user_id', $user->user_id);
            }
        ])->findOrFail($id);

        $progress = $materi->progresses->first();
        $urutanSelesai = $progress->urutan_selesai ?? 0;

        $steps = collect();

        foreach ($materi->subMateris as $sub) {
            $steps->push([
                'type' => 'sub_materi',
                'judul' => $sub->judul,
                'urutan' => $sub->urutan_sub_materi,
                'file' => $sub->file_materi,
                'deskripsi' => $sub->deskripsi
                
            ]);
        }

        foreach ($materi->postTests as $test) {
            $skorUser = null;
            if ($progress) {
                $skorUser = SkorUser::where('progress_id', $progress->progress_id)
                    ->where('post_test_id', $test->post_test_id)
                    ->first();
            }

            $steps->push([
                'type' => 'post_test',
                'judul' => 'Kuis',
                'urutan' => $test->urutan_post_test,
                'jumlah_soal' => $test->soals()->count(),
                'waktu_pengerjaan' => $test->waktu_pengerjaan,
                'max_attempt' => $test->ulang_post_test,

                // 🔥 TAMBAHAN PENTING
                'sudah_mengerjakan' => $skorUser ? true : false,
                'skor_tertinggi' => $skorUser->skor ?? 0,
                'percobaan' => $skorUser->percobaan ?? 0
            ]);
        }

        $steps = $steps->sortBy('urutan')->values();

        $totalStep = $steps->count();

        $progressPercent = $totalStep > 0
            ? round(($urutanSelesai / $totalStep) * 100)
            : 0;

        return response()->json([
            'data' => [
                'materi_id' => $materi->materi_id,
                'judul' => $materi->judul,
                'steps' => $steps,
                'urutan_selesai' => $urutanSelesai,
                'progress_percent' => $progressPercent
            ]
        ]);
    }

    //update progress materi
    public function updateProgress(Request $request)
    {
        $user = Auth::user();

        $materiId = $request->materi_id;
        $urutan = $request->urutan;

        $materi = Materi::with(['subMateris','postTests'])->findOrFail($materiId);

        $totalStep =
            $materi->subMateris->count() +
            $materi->postTests->count();

        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->user_id,
                'materi_id' => $materiId
            ],
            [
                'urutan_selesai' => 0,
                'status' => 'Progres'
            ]
        );

        if($urutan > $progress->urutan_selesai){

            $progress->urutan_selesai = $urutan;

            if($urutan >= $totalStep){
                $progress->status = 'Selesai';
            }else{
                $progress->status = 'Progres';
            }

            $progress->save();
        }

        return response()->json([
            'message' => 'Progress updated'
        ]);
    }

    //post test
    public function getSoalPostTest($materiId)
    {
        $materi = Materi::with([
            'postTests.soals'
        ])->findOrFail($materiId);

        $postTest = $materi->postTests()->first();

        $totalSoal = $postTest->soals->count();
        $poinPerSoal = round(100 / $totalSoal, 2);

        $soals = $postTest->soals->map(function ($soal) use ($poinPerSoal) {
            return [
                'soal_id' => $soal->soal_id,
                'post_test_id' => $soal->post_test_id,
                'urutan_soal' => $soal->urutan_soal,
                'status_pilihan' => $soal->status_pilihan,
                'soal' => $soal->soal,

                // poin sekarang dihitung otomatis
                'poin' => $poinPerSoal,

                'pilihan_1' => $soal->pilihan_1,
                'pilihan_2' => $soal->pilihan_2,
                'pilihan_3' => $soal->pilihan_3,
                'pilihan_4' => $soal->pilihan_4,
                'pilihan_5' => $soal->pilihan_5,
            ];
        });

        return response()->json([
            'data' => [
                'total_soal' => $soals->count(),
                'waktu_pengerjaan' => $postTest->waktu_pengerjaan,
                'soals' => $soals->values()
            ]
        ]);
    }

    //Penghitungan skor post test
    public function submitPostTest(Request $request)
    {
        $user = Auth::user();

        $materiId = $request->materi_id;
        $jawabanUser = $request->jawaban;

        $materi = Materi::with('postTests.soals')->findOrFail($materiId);

        $postTest = $materi->postTests()->first();
        $soals = $postTest->soals;


        $totalSoal = $soals->count();
        $nilaiPerSoal = 100 / $totalSoal;

        $skor = 0;
        foreach($soals as $soal){

            $soalId = $soal->soal_id;

            if(!isset($jawabanUser[$soalId])) continue;

            $jawaban = $jawabanUser[$soalId];
            $jawabanBenar = array_map('trim', explode(',', $soal->jawaban_benar));

            sort($jawaban);
            sort($jawabanBenar);

            if($jawaban == $jawabanBenar){
                $skor += $nilaiPerSoal;
            }
        }

        $progress = UserProgress::where('user_id',$user->user_id)
            ->where('materi_id',$materiId)
            ->first();

        $skorUser = SkorUser::where('progress_id', $progress->progress_id)
            ->where('post_test_id', $postTest->post_test_id)
            ->first();

        if($skorUser){
            $skorBaru = round($skor);

            if($skorBaru > $skorUser->skor){
                $skorUser->update([
                    'skor' => $skorBaru
                ]);
            }
        }

        $materiLengkap = Materi::with(['subMateris','postTests'])->find($materiId);

        $totalStep =
            $materiLengkap->subMateris->count() +
            $materiLengkap->postTests->count();

        if($progress->urutan_selesai < $totalStep){

            $progress->urutan_selesai = $progress->urutan_selesai + 1;

            if($progress->urutan_selesai >= $totalStep){
                $progress->status = 'Selesai';
            }else{
                $progress->status = 'Progres';
            }

            $progress->save();
        }

        $totalPostTest = $materi->postTests()->count();

        $totalDikerjakan = SkorUser::where('progress_id',$progress->progress_id)->count();

        if($totalDikerjakan == $totalPostTest){

            $totalSkor = SkorUser::where('progress_id',$progress->progress_id)
                ->sum('skor');

            $skorAkhir = $totalSkor / $totalPostTest;

            $progress->update([
                'skor_total' => $skorAkhir
            ]);
        }

        return response()->json([
            'skor' => round($skor)
        ]);
    }

    public function startPostTest(Request $request)
    {
        $user = Auth::user();
        $materiId = $request->materi_id;

        $materi = Materi::with('postTests')->findOrFail($materiId);
        $postTest = $materi->postTests()->first();

        // ambil progress user
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->user_id,
                'materi_id' => $materiId
            ],
            [
                'urutan_selesai' => 0,
                'status' => 'Progres'
            ]
        );

        // cek apakah sudah pernah ada
        $skorUser = SkorUser::where('progress_id', $progress->progress_id)
            ->where('post_test_id', $postTest->post_test_id)
            ->first();

        // jika sudah ada → tambah percobaan
        if($skorUser){

            // VALIDASI: batasi percobaan
            if($skorUser->percobaan >= $postTest->ulang_post_test){
                return response()->json([
                    'message' => 'Percobaan sudah habis'
                ], 403);
            }

            $skorUser->increment('percobaan');

            // // reset skor (karena mulai ulang)
            // $skorUser->update([
            //     'skor' => null
            // ]);

        }else{

            // pertama kali
            SkorUser::create([
                'progress_id' => $progress->progress_id,
                'post_test_id' => $postTest->post_test_id,
                'percobaan' => 1,
                'skor' => 0
            ]);
        }

        return response()->json([
            'message' => 'Post test dimulai'
        ]);
    }


}