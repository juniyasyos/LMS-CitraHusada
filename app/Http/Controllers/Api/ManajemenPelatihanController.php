<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Kategori;
use App\Models\UnitKerja;
use App\Models\JenisTenaga;
use App\Models\LogAktivitas;
use App\Models\UserProgress;
use App\Models\SubMateri;
use App\Models\PostTest;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ManajemenPelatihanController extends Controller
{
    public function index()
    {
        return view('SuperAdmin_Views.manajemen-pelatihan');
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'terbaru');
        $today = now()->startOfDay();

        $query = Materi::aktif()->with(['kategori', 'unitKerjas', 'jenisTenagas'])->withCount('subMateris');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('subjudul', 'LIKE', "%{$search}%");
            });
        }

        $query->where('tanggal_selesai', '>=', $today);

        if ($sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $materis = $query->paginate(12);

        $kategoris = Kategori::all();
        $unitKerjas = UnitKerja::all();
        $jenisTenagas = JenisTenaga::all();

        return response()->json([
            'success' => true,
            'data' => [
                'materis' => $materis,
                'kategoris' => $kategoris,
                'unitKerjas' => $unitKerjas,
                'jenisTenagas' => $jenisTenagas
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nama_pemateri' => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'jam_pelajaran' => 'required|integer|min:1',
            'tanggal_upload' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_upload',
            'kategori_id' => 'required|exists:kategoris,kategori_id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'unit_kerja_ids' => 'nullable|string',
            'jenis_tenaga_ids' => 'nullable|string',
            'nomor_surat' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('thumbnail')) {
                $imagePath = $request->file('thumbnail')->store('materi/Cover');
            }

            $materi = Materi::create([
                'judul' => $request->judul,
                'nama_pemateri' => $request->nama_pemateri,
                'subjudul' => $request->subjudul,
                'deskripsi' => $request->deskripsi,
                'jam_pelajaran' => $request->jam_pelajaran,
                'tanggal_upload' => $request->tanggal_upload,
                'tanggal_selesai' => $request->tanggal_selesai,
                'kategori_id' => $request->kategori_id,
                'image_path' => $imagePath,
                'nomor_surat' => $request->nomor_surat,
            ]);

            if ($request->filled('unit_kerja_ids')) {
                $materi->unitKerjas()->sync(explode(',', $request->unit_kerja_ids));
            }
            if ($request->filled('jenis_tenaga_ids')) {
                $materi->jenisTenagas()->sync(explode(',', $request->jenis_tenaga_ids));
            }

            $this->logActivity($request, 'Insert', 'materis', $materi->materi_id, "Menambah folder pelatihan baru: [{$materi->judul}]");

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data pelatihan berhasil ditambahkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan pelatihan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nama_pemateri' => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'jam_pelajaran' => 'required|integer|min:1',
            'tanggal_upload' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_upload',
            'kategori_id' => 'required|exists:kategoris,kategori_id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'unit_kerja_ids' => 'nullable|string',
            'jenis_tenaga_ids' => 'nullable|string',
            'nomor_surat' => 'nullable|string|max:255',
        ]);

        $materi = Materi::find($id);
        if (!$materi) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('thumbnail')) {
                if ($materi->image_path)
                    Storage::delete($materi->image_path);
                $materi->image_path = $request->file('thumbnail')->store('materi/Cover');
            }

            $materi->update([
                'judul' => $request->judul,
                'nama_pemateri' => $request->nama_pemateri,
                'subjudul' => $request->subjudul,
                'deskripsi' => $request->deskripsi,
                'jam_pelajaran' => $request->jam_pelajaran,
                'tanggal_upload' => $request->tanggal_upload,
                'tanggal_selesai' => $request->tanggal_selesai,
                'kategori_id' => $request->kategori_id,
                'nomor_surat' => $request->nomor_surat,
            ]);

            $unitKerjas = $request->filled('unit_kerja_ids') ? explode(',', $request->unit_kerja_ids) : [];
            $jenisTenagas = $request->filled('jenis_tenaga_ids') ? explode(',', $request->jenis_tenaga_ids) : [];

            $materi->unitKerjas()->sync($unitKerjas);
            $materi->jenisTenagas()->sync($jenisTenagas);

            $this->logActivity($request, 'Update', 'materis', $materi->materi_id, "Memperbarui data folder pelatihan: [{$materi->judul}]");

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data pelatihan berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui pelatihan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $materi = Materi::find($id);
        if (!$materi) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $judul = $materi->judul;
        $materi->delete();

        $this->logActivity($request, 'Delete', 'materis', $id, "Menghapus folder pelatihan: [{$judul}]");
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    public function archiveIndex()
    {
        return view('SuperAdmin_Views.arsip-pelatihan');
    }

    public function getArchiveData(Request $request)
    {
        $search = $request->input('search');
        $today = now()->startOfDay();

        $query = Materi::aktif()->where('tanggal_selesai', '<', $today)->with('kategori');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('subjudul', 'LIKE', "%{$search}%");
            });
        }

        $materis = $query->latest()->paginate(12);

        return response()->json([
            'success' => true,
            'data' => [
                'materis' => $materis
            ]
        ]);
    }

    public function unarchive(Request $request, $id)
    {
        $request->validate([
            'tanggal_upload' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_upload',
        ]);

        $materi = Materi::find($id);
        if (!$materi) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $materi->update([
            'tanggal_upload' => $request->tanggal_upload,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        // Update status user_progress yang sebelumnya "Sesi Berakhir" (jangan ubah "Selesai")
        $sesiBerakhirProgresses = UserProgress::where('materi_id', $id)
            ->where('status', 'Sesi Berakhir')
            ->get();

        foreach ($sesiBerakhirProgresses as $progress) {
            if ($progress->urutan_selesai == 0) {
                $progress->update(['status' => 'Belum Dimulai']);
            } else {
                $progress->update(['status' => 'Progres']);
            }
        }

        $this->logActivity($request, 'Update', 'materis', $id, "Memulihkan folder [{$materi->judul}] dari arsip (Periode baru: {$request->tanggal_upload} s/d {$request->tanggal_selesai})");
        return response()->json(['success' => true, 'message' => 'Data pelatihan berhasil dipulihkan.']);
    }

    public function destroyFromArchive(Request $request, $id)
    {
        $materi = Materi::find($id);
        if (!$materi) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $judul = $materi->judul;

        $materi->delete();

        $this->logActivity($request, 'Delete', 'materis', $id, "Memindahkan folder [{$judul}] dari arsip ke sampah");
        return response()->json(['success' => true, 'message' => 'Data berhasil dipindahkan ke sampah.']);
    }

    public function trash()
    {
        return view('SuperAdmin_Views.sampah-pelatihan');
    }

    public function getTrashData(Request $request)
    {
        $search = $request->input('search');
        $query = Materi::onlyTrashed()->with('kategori');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('subjudul', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('deleted_at', 'desc');
        $trashedMateris = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => [
                'trashedMateris' => $trashedMateris
            ]
        ]);
    }

    public function restore($id)
    {
        $materi = Materi::onlyTrashed()->findOrFail($id);
        $materi->restore();
        return response()->json(['success' => true, 'message' => 'Data pelatihan berhasil dipulihkan.']);
    }

    public function forceDestroy($id)
    {
        $materi = Materi::onlyTrashed()->with(['subMateris', 'postTests.soals'])->findOrFail($id);

        DB::beginTransaction();
        try {
            // 1. Hapus file thumbnail fisik
            if ($materi->image_path) {
                Storage::delete($materi->image_path);
            }
            
            // 2. Hapus file sub-materi fisik & null-kan path-nya
            foreach ($materi->subMateris as $subMateri) {
                if ($subMateri->file_materi) {
                    Storage::delete($subMateri->file_materi);
                }
                $subMateri->update(['file_materi' => null]);
            }

            // 3. Kembalikan dari soft-delete (agar ID-nya hidup kembali untuk tabel sertifikat)
            $materi->restore(); 
            
            // 4. Tandai sebagai sudah dibersihkan (is_cleaned) dan kosongkan link image
            $materi->update([
                'is_cleaned' => true,
                'image_path' => null,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'File media berhasil dihapus. Data riwayat pelatihan berhasil diamankan di database.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal mengarsipkan data: ' . $e->getMessage()], 500);
        }
    }

    public function showMateriContent($materiId)
    {
        return view('SuperAdmin_Views.daftar-materi-kuis-superadmin', compact('materiId'));
    }

    public function showArchivedMateriContent($materiId)
    {
        $readOnly = true;
        return view('SuperAdmin_Views.daftar-materi-kuis-superadmin', compact('materiId', 'readOnly'));
    }

    public function getContentData($materiId)
    {
        $materi = Materi::with(['subMateris', 'postTests.soals', 'kategori'])->find($materiId);
        if (!$materi) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $contents = collect();

        foreach ($materi->subMateris as $sub) {
            $sub->type = 'materi';
            $sub->sort_order = $sub->urutan_sub_materi;
            $contents->push($sub);
        }

        foreach ($materi->postTests as $test) {
            $test->type = 'kuis';
            $test->sort_order = $test->urutan_post_test;
            $contents->push($test);
        }

        $contents = $contents->sortBy('sort_order')->values();

        foreach ($contents as $item) {
            $item->jumlah_pengerjaan = UserProgress::where('materi_id', $materiId)
                ->where('urutan_selesai', '>=', $item->sort_order)
                ->distinct('user_id')
                ->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'materi' => $materi,
                'contents' => $contents
            ]
        ]);
    }

    public function storeSubMateri(Request $request, $materiId)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_materi' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    $size = $value->getSize() / 1024; // dalam KB

                    $videoExt = ['mp4', 'mov', 'avi', 'mkv'];

                    if (in_array($ext, $videoExt)) {
                        if ($size > 10240) { // 10MB = 10240 KB
                            $fail('Ukuran file video terlalu besar, maksimal 10MB.');
                        }
                    } else {
                        if ($size > 5120) { // 5MB = 5120 KB
                            $fail('Ukuran file dokumen terlalu besar, maksimal 5MB.');
                        }
                    }
                },
            ],
        ], [
            'file_materi.required' => 'File materi wajib diisi/diunggah.',
            'judul.required' => 'Judul materi wajib diisi.',
        ]);


        $materi = Materi::find($materiId);
        if (!$materi) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $existingCount = $materi->subMateris->count() + $materi->postTests->count();
        $newOrder = $existingCount + 1;

        $file = $request->file('file_materi');
        $ext = $file->getClientOriginalExtension();
        $folder = 'materi/PDF';
        if (in_array(strtolower($ext), ['mp4', 'mov', 'avi', 'mkv'])) {
            $folder = 'materi/Video';
        } elseif (in_array(strtolower($ext), ['ppt', 'pptx'])) {
            $folder = 'materi/PPT';
        }

        $path = $file->store($folder);

        SubMateri::create([
            'materi_id' => $materiId,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_materi' => $path,
            'urutan_sub_materi' => $newOrder,
        ]);

        $this->logActivity($request, 'Insert', 'sub_materis', null, "Menambah materi baru: [{$request->judul}]");
        return response()->json(['success' => true, 'message' => 'Materi berhasil diunggah!']);
    }

    public function updateSubMateri(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_materi' => [
                'nullable',
                'file',
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    $size = $value->getSize() / 1024; // dalam KB

                    $videoExt = ['mp4', 'mov', 'avi', 'mkv'];

                    if (in_array($ext, $videoExt)) {
                        if ($size > 10240) { // 10MB
                            $fail('Ukuran file video terlalu besar, maksimal 10MB.');
                        }
                    } else {
                        if ($size > 5120) { // 5MB
                            $fail('Ukuran file dokumen terlalu besar, maksimal 5MB.');
                        }
                    }
                },
            ],
        ], [
            'judul.required' => 'Judul materi wajib diisi.',
        ]);

        $sub = SubMateri::find($id);
        if (!$sub) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        if ($request->hasFile('file_materi')) {
            if ($sub->file_materi)
                Storage::delete($sub->file_materi);
            $file = $request->file('file_materi');
            $ext = $file->getClientOriginalExtension();
            $folder = 'materi/PDF';
            if (in_array(strtolower($ext), ['mp4', 'mov', 'avi', 'mkv'])) {
                $folder = 'materi/Video';
            } elseif (in_array(strtolower($ext), ['ppt', 'pptx'])) {
                $folder = 'materi/PPT';
            }
            $sub->file_materi = $file->store($folder);
        }

        $sub->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        $this->logActivity($request, 'Update', 'sub_materis', $id, "Mengupdate materi: [{$sub->judul}]");
        return response()->json(['success' => true, 'message' => 'Materi berhasil diperbarui!']);
    }

    public function destroySubMateri(Request $request, $id)
    {
        $sub = SubMateri::find($id);
        if (!$sub) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $judul = $sub->judul;

        if ($sub->file_materi)
            Storage::delete($sub->file_materi);
        $sub->delete();

        $this->logActivity($request, 'Delete', 'sub_materis', $id, "Menghapus materi: [{$judul}]");
        return response()->json(['success' => true, 'message' => 'Materi berhasil dihapus!']);
    }

    public function storePostTest(Request $request, $materiId)
    {
        // To simplify, we receive questions as a JSON string and parse it, because FormData appends objects awkwardly
        $questions = is_string($request->questions) ? json_decode($request->questions, true) : $request->questions;

        $materi = Materi::find($materiId);
        if (!$materi) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $existingCount = $materi->subMateris->count() + $materi->postTests->count();
        $newOrder = $existingCount + 1;

        DB::beginTransaction();
        try {
            $postTest = PostTest::create([
                'materi_id' => $materiId,
                'judul' => $request->judul,
                'urutan_post_test' => $newOrder,
                'waktu_pengerjaan' => $request->waktu_pengerjaan,
                'ulang_post_test' => $request->ulang_post_test,
                'pretest' => $request->boolean('pretest'),
            ]);

            if ($questions && is_array($questions)) {
                foreach ($questions as $index => $q) {
                    $jawabanRaw = $q['jawaban_benar'];
                    $jawabanConverted = str_replace(
                        ['A', 'B', 'C', 'D', 'E', 'a', 'b', 'c', 'd', 'e'],
                        ['1', '2', '3', '4', '5', '1', '2', '3', '4', '5'],
                        $jawabanRaw
                    );

                    Soal::create([
                        'post_test_id' => $postTest->post_test_id,
                        'urutan_soal' => $index + 1,
                        'status_pilihan' => $q['status_pilihan'] === 'true' || $q['status_pilihan'] === true ? 1 : 0,
                        'soal' => $q['soal'],
                        'pilihan_1' => $q['options'][0] ?? null,
                        'pilihan_2' => $q['options'][1] ?? null,
                        'pilihan_3' => $q['options'][2] ?? null,
                        'pilihan_4' => $q['options'][3] ?? null,
                        'pilihan_5' => $q['options'][4] ?? null,
                        'jawaban_benar' => $jawabanConverted,
                        'poin' => 10,
                    ]);
                }
            }

            $this->logActivity($request, 'Insert', 'post_tests', $postTest->post_test_id, "Menambah kuis baru: [{$request->judul}]");
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Kuis berhasil dibuat!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan kuis: ' . $e->getMessage()], 500);
        }
    }

    public function updatePostTest(Request $request, $id)
    {
        $questions = is_string($request->questions) ? json_decode($request->questions, true) : $request->questions;
        $postTest = PostTest::find($id);
        if (!$postTest) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            $postTest->update([
                'judul' => $request->judul,
                'waktu_pengerjaan' => $request->waktu_pengerjaan,
                'ulang_post_test' => $request->ulang_post_test,
                'pretest' => $request->boolean('pretest'),
            ]);

            $postTest->soals()->delete();

            if ($questions && is_array($questions)) {
                foreach ($questions as $index => $q) {
                    $jawabanRaw = $q['jawaban_benar'];
                    $jawabanConverted = str_replace(
                        ['A', 'B', 'C', 'D', 'E', 'a', 'b', 'c', 'd', 'e'],
                        ['1', '2', '3', '4', '5', '1', '2', '3', '4', '5'],
                        $jawabanRaw
                    );

                    Soal::create([
                        'post_test_id' => $postTest->post_test_id,
                        'urutan_soal' => $index + 1,
                        'status_pilihan' => $q['status_pilihan'] === 'true' || $q['status_pilihan'] === true ? 1 : 0,
                        'soal' => $q['soal'],
                        'pilihan_1' => $q['options'][0] ?? null,
                        'pilihan_2' => $q['options'][1] ?? null,
                        'pilihan_3' => $q['options'][2] ?? null,
                        'pilihan_4' => $q['options'][3] ?? null,
                        'pilihan_5' => $q['options'][4] ?? null,
                        'jawaban_benar' => $jawabanConverted,
                        'poin' => 10,
                    ]);
                }
            }

            $this->logActivity($request, 'Update', 'post_tests', $id, "Mengupdate kuis: [{$postTest->judul}]");
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Kuis berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui kuis: ' . $e->getMessage()], 500);
        }
    }

    public function destroyPostTest(Request $request, $id)
    {
        $postTest = PostTest::find($id);
        if (!$postTest) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $judul = $postTest->judul;

        DB::beginTransaction();
        try {
            $postTest->soals()->delete();
            $postTest->delete();

            $this->logActivity($request, 'Delete', 'post_tests', $id, "Menghapus kuis: [{$judul}]");
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Kuis berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus kuis: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download file sub-materi dan catat log.
     */
    // public function downloadSubMateri(Request $request, $id)
    // {
    //     $sub = SubMateri::findOrFail($id);
    //     $filePath = $sub->file_materi;
    //     $judul = $sub->judul;

    //     if (!$filePath || !Storage::disk('s3')->exists($filePath)) {
    //         return back()->with('error', 'File tidak ditemukan di server.');
    //     }

    //     // Log Aktivitas
    //     $this->logActivity($request, 'Download', 'sub_materis', $id, "Mengunduh file materi: [{$judul}]");

    //     return Storage::disk('s3')->download($filePath);
    // }

    /**
     * Auto-cleanup: Dipanggil oleh scheduler, hapus permanen materi
     * yang sudah di sampah lebih dari 30 hari.
     */
    public static function autoCleanTrash()
    {
        $cutoff = now()->subDays(30);
        $oldTrashed = Materi::onlyTrashed()
            ->with(['subMateris', 'postTests.soals'])
            ->where('deleted_at', '<=', $cutoff)
            ->get();

        foreach ($oldTrashed as $materi) {
            // Hapus thumbnail
            if ($materi->image_path) {
                Storage::delete($materi->image_path);
            }

            // Hapus file sub_materi
            foreach ($materi->subMateris as $subMateri) {
                if ($subMateri->file_materi) {
                    Storage::delete($subMateri->file_materi);
                }
                $subMateri->delete();
            }

            // Hapus post_test & soals
            foreach ($materi->postTests as $postTest) {
                $postTest->soals()->delete();
                $postTest->delete();
            }

            // Detach pivot
            $materi->unitKerjas()->detach();
            $materi->jenisTenagas()->detach();

            // Force delete materi
            $materi->forceDelete();
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
