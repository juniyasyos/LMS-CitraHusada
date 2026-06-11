<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\UserProgress;
use App\Models\SkorUser;
use App\Models\Notification;
use App\Models\User;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MateriUserController extends Controller
{
    /**
     * Daftar materi untuk halaman pembelajaran karyawan.
     * Endpoint: GET /api/materi-user
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $materis = Materi::available()

            // SEARCH
            ->when($search, function ($query) use ($search) {
                $query->where(
                    function ($q) use ($search) {
                        $q->where('judul', 'like', '%' . $search . '%')
                            ->orWhere('subjudul', 'like', '%' . $search . '%');
                    }
                );
            })

            // FILTER BERDASARKAN JENIS TENAGA / UNIT
            ->where(function ($query) use ($user) {

                $query->whereHas(
                    'materiJenisTenagas',
                    function ($q) use ($user) {
                        $q->where('jenis_tenaga_id', $user->jenis_tenaga_id);
                    }
                )
                    ->orWhereHas(
                        'materiUnitKerjas',
                        function ($q) use ($user) {
                            $q->where('unit_kerja_id', $user->unit_kerja_id);
                        }
                    );

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


        $materis = $materis->map(function ($materi) use ($user) {

            $totalSub = $materi->subMateris->count();
            $totalTest = $materi->postTests->count();

            $totalStep = $totalSub + $totalTest;

            $progress = $materi->progresses->first();

            $urutan = $progress->urutan_selesai ?? 0;

            $progressPercent = $totalStep > 0
                ? round(($urutan / $totalStep) * 100)
                : 0;

            $status = $progress->status ?? 'Belum Dimulai';

            // TASK 1: DEADLINE VALIDATOR
            $now = Carbon::now();

            if (!empty($materi->tanggal_selesai)) {
                $deadline = Carbon::parse($materi->tanggal_selesai);

                if ($status !== 'Selesai' && $now->startOfDay()->gt($deadline->copy()->endOfDay())) {
                    $status = 'Sesi Berakhir';
                    UserProgress::updateOrCreate(
                        [
                            'user_id' => $user->user_id,
                            'materi_id' => $materi->materi_id
                        ],
                        [
                            'status' => 'Sesi Berakhir'
                        ]
                    );
                }

                // TASK 2: SMART NOTIFICATION
                if ($status !== 'Selesai' && $status !== 'Sesi Berakhir' && !$now->startOfDay()->gt($deadline->copy()->endOfDay())) {
                    $daysRemaining = $now->copy()->startOfDay()->diffInDays($deadline->copy()->endOfDay(), false);

                    if ($daysRemaining >= 0 && $daysRemaining <= 7) {
                        $notifExists = Notification::where('user_id', $user->user_id)
                            ->where('type', 'deadline_reminder')
                            ->where('title', 'Deadline Materi: ' . $materi->judul)
                            ->exists();

                        if (!$notifExists) {
                            $hari = ceil($daysRemaining) > 0 ? ceil($daysRemaining) . ' hari' : 'hari ini';
                            Notification::create([
                                'user_id' => $user->user_id,
                                'type' => 'deadline_reminder',
                                'title' => 'Deadline Materi: ' . $materi->judul,
                                'message' => 'Materi "' . $materi->judul . '" akan berakhir ' . $hari . '. Segera selesaikan sebelum ujian ditutup!',
                                'is_read' => false
                            ]);
                        }
                    }
                }
            }

            return [
                'materi_id' => $materi->materi_id,
                'judul' => $materi->judul,
                'subjudul' => $materi->subjudul,
                'image' => $materi->image_path ? Storage::disk('s3')->url($materi->image_path) : null,
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
                $materis = $materis->filter(function ($item) {
                    return in_array($item['status'], ['Selesai', 'Sesi Berakhir']);
                });
            }

            $materis = $materis->values();
        }

        $totalCount = $materis->count();

        // APPLY LIMIT
        $limit = (int) $request->query('limit', 6);
        $materis = $materis->take($limit)->values();

        return response()->json([
            'data' => $materis,
            'total' => $totalCount,
            'limit' => $limit
        ]);
    }

    /**
     * Detail materi (langkah-langkah sub materi & kuis).
     * Endpoint: GET /api/materi-user/{id}
     */
    public function show($id)
    {
        $user = Auth::user();

        $materi = Materi::available()->with([
            'subMateris',
            'postTests',
            'progresses' => function ($q) use ($user) {
                $q->where('user_id', $user->user_id);
            }
        ])->findOrFail($id);

        $progress = $materi->progresses->first();
        $urutanSelesai = $progress->urutan_selesai ?? 0;
        $status = $progress->status ?? 'Belum Dimulai';

        $now = Carbon::now();

        if (!empty($materi->tanggal_selesai)) {
            $deadline = Carbon::parse($materi->tanggal_selesai);

            if ($status !== 'Selesai' && $now->startOfDay()->gt($deadline->copy()->endOfDay())) {
                $status = 'Sesi Berakhir';
                UserProgress::updateOrCreate(
                    [
                        'user_id' => $user->user_id,
                        'materi_id' => $materi->materi_id
                    ],
                    [
                        'status' => 'Sesi Berakhir'
                    ]
                );
            }
        }

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

        // AMBIL SEMUA SKOR SEKALIGUS (OPTIMIZATION)
        $progressId = $progress->progress_id ?? 0;
        $skorUsers = SkorUser::where('progress_id', $progressId)->get();

        // POST TEST
        foreach ($materi->postTests as $test) {
            $skorData = $skorUsers->where('post_test_id', $test->post_test_id)->first();

            $steps->push([
                'type' => 'post_test',
                'id' => $test->post_test_id,
                'judul' => 'Kuis: ' . $test->judul,
                'urutan' => $test->urutan_post_test,
                'skor' => $skorData->skor ?? 0,
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
                'urutan_selesai' => $urutanSelesai,
                'status' => $status
            ]
        ]);
    }

    /**
     * Lanjutkan materi (termasuk data file, soal kuis, dll).
     * Endpoint: GET /api/materi-lanjutkan/{id}
     */
    public function lanjutkan($id)
    {
        $user = Auth::user();

        $materi = Materi::available()->with([
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
                'file' => $sub->file_materi ? Storage::disk('s3')->url($sub->file_materi): null,
                // 'urutan' => $sub->urutan_sub_materi,
                'deskripsi' => $sub->deskripsi

            ]);
        }

        // MENGHINDARI N+1 QUERY SKOR_USER
        $progressId = $progress->progress_id ?? 0;
        $skorUsers = $progress ? SkorUser::where('progress_id', $progressId)->get() : collect();

        foreach ($materi->postTests as $test) {
            $skorUser = $skorUsers->where('post_test_id', $test->post_test_id)->first();

            $steps->push([
                'type' => 'post_test',
                'id' => $test->post_test_id,
                'judul' => 'Kuis',
                'urutan' => $test->urutan_post_test,
                'jumlah_soal' => $test->soals()->count(),
                'waktu_pengerjaan' => $test->waktu_pengerjaan,
                'max_attempt' => $test->ulang_post_test,

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

    /**
     * Update progress materi user.
     * Endpoint: POST /api/progress/update
     */
    public function updateProgress(Request $request)
    {
        $user = Auth::user();

        $materiId = $request->materi_id;
        $urutan = $request->urutan;

        $materi = Materi::available()->with(['subMateris', 'postTests'])->findOrFail($materiId);

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

        if ($urutan > $progress->urutan_selesai + 1) {
            return response()->json([
                'message' => 'Tidak dapat melompati materi!'
            ], 403);
        }

        if ($urutan > $progress->urutan_selesai) {

            $progress->urutan_selesai = $urutan;

            if ($urutan >= $totalStep) {
                // Cek skor_total jika materi ini memiliki post test (selain pretest)
                $totalPostTest = $materi->postTests->where('pretest', false)->count();
                if ($totalPostTest > 0) {
                    $kkm = 75;
                    if ($progress->skor_total >= $kkm) {
                        if ($progress->status !== 'Selesai') {
                            $progress->status = 'Selesai';

                            Sertifikat::firstOrCreate([
                                'user_id' => $user->user_id,
                                'materi_id' => $materiId
                            ], [
                                'status' => 'Belum Disetujui'
                            ]);
                        }
                    } else {
                        $progress->status = 'Progres';
                    }
                } else {
                    if ($progress->status !== 'Selesai') {
                        $progress->status = 'Selesai';

                        Sertifikat::firstOrCreate([
                            'user_id' => $user->user_id,
                            'materi_id' => $materiId
                        ], [
                            'status' => 'Belum Disetujui'
                        ]);
                    }
                }
            } else {
                $progress->status = 'Progres';
            }

            $progress->save();
        }

        $lockbackResult = $this->checkAndResetProgress($user->user_id, $materiId);

        $response = ['message' => 'Progress updated'];
        if ($lockbackResult && $lockbackResult['is_locked_back']) {
            $response['is_locked_back'] = true;
            $response['lockback_message'] = $lockbackResult['message'];
        }

        return response()->json($response);
    }

    /**
     * Ambil soal post test.
     * Endpoint: GET /api/post-test-soal/{materiId}
     */
    public function getSoalPostTest(Request $request, $materiId)
    {
        $postTestId = $request->query('post_test_id');
        $materi = Materi::available()->with([
            'postTests.soals'
        ])->findOrFail($materiId);

        $postTest = $materi->postTests->where('post_test_id', $postTestId)->first();
        if (!$postTest) {
            $postTest = $materi->postTests()->first(); // Fallback
        }

        // VALIDASI SESSION: MENCEGAH REFRESH / MENGULANG TANPA MULAI RESMI
        if (!session()->has('quiz_active_' . $postTest->post_test_id)) {
            return response()->json([
                'message' => 'Sesi kuis tidak valid atau sudah berakhir.'
            ], 403);
        }

        // LANGSUNG HAPUS SESSION MEMASTIKAN SATU KALI AKSES KUIS (SATU KALI LOAD PAGE)
        session()->forget('quiz_active_' . $postTest->post_test_id);

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

    /**
     * Submit jawaban post test dan hitung skor.
     * Endpoint: POST /api/post-test-submit
     */
    public function submitPostTest(Request $request)
    {
        $user = Auth::user();
        $materiId = $request->materi_id;
        $postTestId = $request->post_test_id;
        $jawabanUser = $request->jawaban;
        $waktuPengerjaan = $request->waktu_pengerjaan ?? 0;

        $materi = Materi::available()->with(['postTests.soals', 'subMateris'])->findOrFail($materiId);
        $postTest = $materi->postTests->where('post_test_id', $postTestId)->first();
        if (!$postTest) {
            $postTest = $materi->postTests()->first(); // Fallback
        }

        // 1. Hitung Skor Kuis yang baru saja dikerjakan
        $soals = $postTest->soals;
        $totalSoal = $soals->count();
        $nilaiPerSoal = 100 / $totalSoal;
        $skorTerhitung = 0;

        foreach ($soals as $soal) {
            $soalId = $soal->soal_id;
            if (!isset($jawabanUser[$soalId]))
                continue;

            $jawaban = $jawabanUser[$soalId];
            $jawabanBenar = array_map('trim', explode(',', $soal->jawaban_benar));
            sort($jawaban);
            sort($jawabanBenar);

            if ($jawaban == $jawabanBenar) {
                $skorTerhitung += $nilaiPerSoal;
            }
        }
        $skorBaru = round($skorTerhitung);

        // 2. Update atau Create SkorUser
        $progress = UserProgress::where('user_id', $user->user_id)
            ->where('materi_id', $materiId)
            ->first();

        if ($progress) {
            $skorUser = SkorUser::where('progress_id', $progress->progress_id)
                ->where('post_test_id', $postTest->post_test_id)
                ->first();

            if ($skorUser) {
                // Simpan skor jika lebih tinggi dari sebelumnya (High Score)
                $skorUser->skor = max($skorUser->skor ?? 0, $skorBaru);
                $skorUser->waktu_pengerjaan = $waktuPengerjaan;
                $skorUser->save();
            }
        }

        // 3. Hitung Rata-rata Skor dari SEMUA kuis (selain pretest) yang ada di materi ini
        $nonPretests = $materi->postTests->where('pretest', false);
        $totalPostTestCount = $nonPretests->count();

        // Ambil semua skor yang sudah dikerjakan untuk materi ini
        $skorDikerjakan = SkorUser::where('progress_id', $progress->progress_id)->get();
        
        if ($totalPostTestCount > 0) {
            $nonPretestIds = $nonPretests->pluck('post_test_id');
            $totalSkorSaatIni = $skorDikerjakan->whereIn('post_test_id', $nonPretestIds)->sum('skor');
            $rataRataSkor = $totalSkorSaatIni / $totalPostTestCount;
        } else {
            $rataRataSkor = 0;
        }

        // 4. Update Progress & Status
        $totalSteps = $materi->subMateris->count() + $materi->postTests->count();

        // User tetap boleh lanjut ke step berikutnya meskipun skor kuis ini kecil
        if ($progress->urutan_selesai < $postTest->urutan_post_test) {
            $progress->urutan_selesai = $postTest->urutan_post_test;
        }

        $kkm = 75;
        // Cek: Apakah sudah sampai di step terakhir DAN rata-rata lulus KKM (jika ada kuis non-pretest)?
        if ($progress->urutan_selesai >= $totalSteps && ($totalPostTestCount == 0 || $rataRataSkor >= $kkm)) {
            if ($progress->status !== 'Selesai') {
                $progress->status = 'Selesai';

                Sertifikat::firstOrCreate([
                    'user_id' => $user->user_id,
                    'materi_id' => $materiId
                ], [
                    'status' => 'Belum Disetujui'
                ]);
            }
        } else {
            $progress->status = 'Progres';
        }

        $progress->skor_total = $rataRataSkor;
        $progress->save();

        $lockbackResult = $this->checkAndResetProgress($user->user_id, $materiId);
        if ($lockbackResult && $lockbackResult['is_locked_back']) {
            $progress->refresh();
        }

        return response()->json([
            'skor_kuis_ini' => $skorBaru,
            'rata_rata_sekarang' => round($rataRataSkor, 2),
            'lulus_materi' => $progress->status == 'Selesai',
            'next_step' => $postTest->urutan_post_test + 1,
            'is_last_step' => $postTest->urutan_post_test >= $totalSteps,
            'waktu_pengerjaan' => $waktuPengerjaan,
            'is_locked_back' => $lockbackResult['is_locked_back'] ?? false,
            'lockback_message' => $lockbackResult['message'] ?? ''
        ]);
    }

    /**
     * Cek dan reset progress jika semua percobaan habis & rata-rata di bawah KKM.
     */
    private function checkAndResetProgress($userId, $materiId)
    {
        $materi = Materi::available()->with(['postTests'])->find($materiId);
        if (!$materi)
            return null;

        $progress = UserProgress::where('user_id', $userId)
            ->where('materi_id', $materiId)
            ->first();

        if (!$progress)
            return null;

        $nonPretests = $materi->postTests->where('pretest', false);
        $totalPostTestCount = $nonPretests->count();

        if ($totalPostTestCount == 0)
            return null;

        $skorUsers = SkorUser::where('progress_id', $progress->progress_id)->get();

        $semuaKesempatanHabis = true;
        $totalSkor = 0;

        foreach ($nonPretests as $test) {
            $skorUser = $skorUsers->where('post_test_id', $test->post_test_id)->first();

            if (!$skorUser) {
                // Belum pernah dikerjakan
                $semuaKesempatanHabis = false;
                break;
            }

            $totalSkor += $skorUser->skor ?? 0;

            if ($skorUser->percobaan < $test->ulang_post_test) {
                $semuaKesempatanHabis = false;
            }
        }

        if (!$semuaKesempatanHabis) {
            return ['is_locked_back' => false];
        }

        $rataRataSkor = $totalSkor / $totalPostTestCount;
        $kkm = 75;

        // Kunci kembali progress jika kesempatan habis dan rata-rata tidak lulus
        if ($rataRataSkor < $kkm) {
            $progress->urutan_selesai = 0;
            $progress->status = 'Progres';
            $progress->save();

            // Reset percobaan agar user bisa memulai kembali kuis
            foreach ($skorUsers as $skorUser) {
                $skorUser->percobaan = 0;
                $skorUser->save();
            }

            return [
                'is_locked_back' => true,
                'message' => 'Anda telah kehabisan kesempatan kuis dan nilai rata-rata di bawah KKM. Progress materi diulang kembali dari awal.'
            ];
        }

        return ['is_locked_back' => false];
    }

    /**
     * Mulai post test (validasi percobaan, buat skor awal).
     * Endpoint: POST /api/post-test-start
     */
    public function startPostTest(Request $request)
    {
        $user = Auth::user();
        $materiId = $request->materi_id;
        $postTestId = $request->post_test_id;

        $materi = Materi::available()->with('postTests')->findOrFail($materiId);
        $postTest = $materi->postTests->where('post_test_id', $postTestId)->first();
        if (!$postTest) {
            $postTest = $materi->postTests()->first(); // Fallback
        }

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

        if ($progress->urutan_selesai < $postTest->urutan_post_test - 1) {
            return response()->json([
                'message' => 'Selesaikan materi sebelumnya terlebih dahulu!'
            ], 403);
        }

        // cek apakah sudah pernah ada
        $skorUser = SkorUser::where('progress_id', $progress->progress_id)
            ->where('post_test_id', $postTest->post_test_id)
            ->first();

        // jika sudah ada → tambah percobaan
        if ($skorUser) {

            // VALIDASI: batasi percobaan
            if ($skorUser->percobaan >= $postTest->ulang_post_test) {
                return response()->json([
                    'message' => 'Percobaan sudah habis'
                ], 403);
            }

            $skorUser->increment('percobaan');

        } else {

            // pertama kali
            SkorUser::create([
                'progress_id' => $progress->progress_id,
                'post_test_id' => $postTest->post_test_id,
                'percobaan' => 1,
                'skor' => 0
            ]);
        }

        // SET SESSION KUIS AKTIF
        session()->put('quiz_active_' . $postTest->post_test_id, true);

        return response()->json([
            'message' => 'Post test dimulai'
        ]);
    }
}
