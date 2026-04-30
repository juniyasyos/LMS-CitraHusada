<?php

namespace App\Http\Controllers;

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
    /**
     * Halaman utama: menampilkan daftar folder pelatihan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'terbaru');
        $arsip = $request->boolean('arsip');

        $today = now()->startOfDay();

        $query = Materi::with('kategori')
            ->withCount('subMateris');

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('subjudul', 'LIKE', "%{$search}%");
            });
        }

        // Automated Archive Logic: Display folders where tanggal_selesai >= today
        $query->where('tanggal_selesai', '>=', $today);

        // Pengurutan
        if ($sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $materis = $query->paginate(12)->withQueryString();

        // Data untuk modal Tambah Folder
        $kategoris = Kategori::all();
        $unitKerjas = UnitKerja::all();
        $jenisTenagas = JenisTenaga::all();

        return view('SuperAdmin_Views.manajemen-pelatihan', compact(
            'materis', 'kategoris', 'unitKerjas', 'jenisTenagas', 'search', 'sort'
        ));
    }

    /**
     * Simpan materi pelatihan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'subjudul'         => 'nullable|string|max:255',
            'deskripsi'        => 'nullable|string',
            'jam_pelajaran'    => 'required|integer|min:1',
            'tanggal_upload'   => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_upload',
            'kategori_id'      => 'required|exists:kategoris,kategori_id',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'unit_kerja_ids'   => 'nullable|array',
            'unit_kerja_ids.*' => 'exists:unit_kerjas,unit_kerja_id',
            'jenis_tenaga_ids'   => 'nullable|array',
            'jenis_tenaga_ids.*' => 'exists:jenis_tenagas,jenis_tenaga_id',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('thumbnail')) {
                $imagePath = $request->file('thumbnail')->store('materi/Cover', 'public');
            }

            $materi = Materi::create([
                'judul'           => $request->judul,
                'subjudul'        => $request->subjudul,
                'deskripsi'       => $request->deskripsi,
                'jam_pelajaran'   => $request->jam_pelajaran,
                'tanggal_upload'  => $request->tanggal_upload,
                'tanggal_selesai' => $request->tanggal_selesai,
                'kategori_id'     => $request->kategori_id,
                'image_path'      => $imagePath,
            ]);

            // Simpan relasi many-to-many
            if ($request->has('unit_kerja_ids')) {
                $materi->unitKerjas()->sync($request->unit_kerja_ids);
            }
            if ($request->has('jenis_tenaga_ids')) {
                $materi->jenisTenagas()->sync($request->jenis_tenaga_ids);
            }

            // Log Aktivitas
            $this->logActivity($request, 'Insert', 'materis', $materi->materi_id, "Menambah folder pelatihan baru: [{$materi->judul}]");

            DB::commit();
            return redirect()->route('manajemen-pelatihan')->with('success', 'Pelatihan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pelatihan: ' . $e->getMessage());
        }
    }

    /**
     * Update data folder pelatihan.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'subjudul'         => 'nullable|string|max:255',
            'deskripsi'        => 'nullable|string',
            'jam_pelajaran'    => 'required|integer|min:1',
            'tanggal_upload'   => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_upload',
            'kategori_id'      => 'required|exists:kategoris,kategori_id',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'unit_kerja_ids'   => 'nullable|array',
            'unit_kerja_ids.*' => 'exists:unit_kerjas,unit_kerja_id',
            'jenis_tenaga_ids'   => 'nullable|array',
            'jenis_tenaga_ids.*' => 'exists:jenis_tenagas,jenis_tenaga_id',
        ]);

        $materi = Materi::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->hasFile('thumbnail')) {
                // Hapus thumbnail lama jika ada
                if ($materi->image_path) {
                    Storage::disk('public')->delete($materi->image_path);
                }
                $materi->image_path = $request->file('thumbnail')->store('materi/Cover', 'public');
            }

            $materi->update([
                'judul'           => $request->judul,
                'subjudul'        => $request->subjudul,
                'deskripsi'       => $request->deskripsi,
                'jam_pelajaran'   => $request->jam_pelajaran,
                'tanggal_upload'  => $request->tanggal_upload,
                'tanggal_selesai' => $request->tanggal_selesai,
                'kategori_id'     => $request->kategori_id,
            ]);

            // Update relasi many-to-many
            $materi->unitKerjas()->sync($request->unit_kerja_ids ?? []);
            $materi->jenisTenagas()->sync($request->jenis_tenaga_ids ?? []);

            // Log Aktivitas
            $this->logActivity($request, 'Update', 'materis', $materi->materi_id, "Memperbarui data folder pelatihan: [{$materi->judul}]");

            DB::commit();
            return redirect()->route('manajemen-pelatihan')->with('success', 'Pelatihan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pelatihan: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete: pindahkan materi ke sampah.
     */
    public function destroy(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        $judul = $materi->judul;
        $materi->delete(); // SoftDelete — hanya set deleted_at

        // Log Aktivitas
        $this->logActivity($request, 'Delete', 'materis', $id, "Menghapus folder pelatihan: [{$judul}]");

        return redirect()->route('manajemen-pelatihan')->with('success', 'Pelatihan dipindahkan ke Sampah.');
    }

    /**
     * Halaman Sampah: menampilkan materi yang di-soft-delete.
     */
    public function trash(Request $request)
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

        $trashedMateris = $query->paginate(12)->withQueryString();

        return view('SuperAdmin_Views.sampah-pelatihan', compact('trashedMateris', 'search'));
    }

    /**
     * Pulihkan materi dari sampah.
     */
    public function restore($id)
    {
        $materi = Materi::onlyTrashed()->findOrFail($id);
        $materi->restore();

        return redirect()->route('pelatihan.trash')->with('success', 'Pelatihan berhasil dipulihkan!');
    }

    /**
     * Hapus permanen: Hard delete materi beserta dependents.
     * PROTEKSI: user_progress dan skor_users TIDAK dihapus.
     */
    public function forceDestroy($id)
    {
        $materi = Materi::onlyTrashed()->with(['subMateris', 'postTests.soals'])->findOrFail($id);

        DB::beginTransaction();
        try {
            // 1. Hapus thumbnail materi
            if ($materi->image_path) {
                Storage::disk('public')->delete($materi->image_path);
            }

            // 2. Hapus file sub_materi dan datanya
            foreach ($materi->subMateris as $subMateri) {
                if ($subMateri->file_materi) {
                    Storage::disk('public')->delete($subMateri->file_materi);
                }
                $subMateri->delete();
            }

            // 3. Hapus post_test dan soals
            foreach ($materi->postTests as $postTest) {
                $postTest->soals()->delete();
                $postTest->delete();
            }

            // 4. Hapus pivot tables (Unit Kerja & Jenis Tenaga)
            $materi->unitKerjas()->detach();
            $materi->jenisTenagas()->detach();

            // Note: user_progress dan skor_users (Protected Tables) TIDAK dihapus.

            // 5. Force delete materi utama
            $materi->forceDelete();

            DB::commit();
            return redirect()->route('pelatihan.trash')->with('success', 'Pelatihan dan komponen terkait (kecuali riwayat user) telah dihapus permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus secara permanen: ' . $e->getMessage());
        }
    }

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
                Storage::disk('public')->delete($materi->image_path);
            }

            // Hapus file sub_materi
            foreach ($materi->subMateris as $subMateri) {
                if ($subMateri->file_materi) {
                    Storage::disk('public')->delete($subMateri->file_materi);
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

    /**
     * Halaman Detail Isi Pelatihan: List Sub-Materi & Kuis.
     */
    public function showMateriContent($materiId)
    {
        $materi = Materi::with(['subMateris', 'postTests.soals'])->findOrFail($materiId);

        // Gabungkan SubMateri dan PostTest ke dalam satu koleksi
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

        // Urutkan berdasarkan order
        $contents = $contents->sortBy('sort_order')->values();

        // Hitung unique users yang sudah sampai ke urutan tersebut (Jumlah Pengerjaan)
        foreach ($contents as $item) {
            $item->jumlah_pengerjaan = UserProgress::where('materi_id', $materiId)
                ->where('urutan_selesai', '>=', $item->sort_order)
                ->distinct('user_id')
                ->count();
        }

        return view('SuperAdmin_Views.daftar-materi-kuis-superadmin', compact('materi', 'contents'));
    }

    /**
     * Simpan Sub-Materi (Materi Uploader).
     */
    public function storeSubMateri(Request $request, $materiId)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_materi' => 'required|file|max:51200', // 50MB
        ]);

        $materi = Materi::findOrFail($materiId);

        // Hitung urutan baru
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

        $path = $file->store($folder, 'public');

        SubMateri::create([
            'materi_id'         => $materiId,
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'file_materi'       => $path,
            'urutan_sub_materi' => $newOrder,
        ]);

        // Log Aktivitas
        $this->logActivity($request, 'Insert', 'sub_materis', null, "Menambah materi baru: [{$request->judul}]");

        return back()->with('success', 'Materi berhasil diunggah!');
    }

    /**
     * Update Sub-Materi.
     */
    public function updateSubMateri(Request $request, $id)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_materi' => 'nullable|file|max:51200',
        ]);

        $sub = SubMateri::findOrFail($id);

        if ($request->hasFile('file_materi')) {
            // Hapus file lama
            if ($sub->file_materi) {
                Storage::disk('public')->delete($sub->file_materi);
            }

            $file = $request->file('file_materi');
            $ext = $file->getClientOriginalExtension();
            $folder = 'materi/PDF';
            if (in_array(strtolower($ext), ['mp4', 'mov', 'avi', 'mkv'])) {
                $folder = 'materi/Video';
            } elseif (in_array(strtolower($ext), ['ppt', 'pptx'])) {
                $folder = 'materi/PPT';
            }
            $sub->file_materi = $file->store($folder, 'public');
        }

        $sub->update([
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        // Log Aktivitas
        $this->logActivity($request, 'Update', 'sub_materis', $id, "Mengupdate materi: [{$sub->judul}]");

        return back()->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Hapus Sub-Materi.
     */
    public function destroySubMateri(Request $request, $id)
    {
        $sub = SubMateri::findOrFail($id);
        $judul = $sub->judul;

        if ($sub->file_materi) {
            Storage::disk('public')->delete($sub->file_materi);
        }

        $sub->delete();

        // Log Aktivitas
        $this->logActivity($request, 'Delete', 'sub_materis', $id, "Menghapus materi: [{$judul}]");

        return back()->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Simpan Kuis (Dynamic Quiz Builder).
     */
    public function storePostTest(Request $request, $materiId)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'waktu_pengerjaan' => 'required|integer|min:1',
            'ulang_post_test'  => 'required|integer|min:1',
            'questions'        => 'required|array|min:1',
            'questions.*.soal' => 'required|string',
            'questions.*.options' => 'required|array|min:2|max:5',
            'questions.*.jawaban_benar' => 'required|string', // comma separated for multiple choice
            'questions.*.status_pilihan' => 'required|boolean',
        ]);

        $materi = Materi::findOrFail($materiId);

        // Hitung urutan baru
        $existingCount = $materi->subMateris->count() + $materi->postTests->count();
        $newOrder = $existingCount + 1;

        DB::beginTransaction();
        try {
            $postTest = PostTest::create([
                'materi_id'        => $materiId,
                'judul'            => $request->judul,
                'urutan_post_test' => $newOrder,
                'waktu_pengerjaan' => $request->waktu_pengerjaan,
                'ulang_post_test'  => $request->ulang_post_test,
            ]);

            foreach ($request->questions as $index => $q) {
                $jawabanRaw = $q['jawaban_benar'];
                $jawabanConverted = str_replace(
                    ['A', 'B', 'C', 'D', 'E', 'a', 'b', 'c', 'd', 'e'],
                    ['1', '2', '3', '4', '5', '1', '2', '3', '4', '5'],
                    $jawabanRaw
                );

                Soal::create([
                    'post_test_id'   => $postTest->post_test_id,
                    'urutan_soal'    => $index + 1,
                    'status_pilihan' => $q['status_pilihan'],
                    'soal'           => $q['soal'],
                    'pilihan_1'      => $q['options'][0] ?? null,
                    'pilihan_2'      => $q['options'][1] ?? null,
                    'pilihan_3'      => $q['options'][2] ?? null,
                    'pilihan_4'      => $q['options'][3] ?? null,
                    'pilihan_5'      => $q['options'][4] ?? null,
                    'jawaban_benar'  => $jawabanConverted,
                    'poin'           => 10,
                ]);
            }

            // Log Aktivitas
            $this->logActivity($request, 'Insert', 'post_tests', $postTest->post_test_id, "Menambah kuis baru: [{$request->judul}]");

            DB::commit();
            return back()->with('success', 'Kuis berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan kuis: ' . $e->getMessage());
        }
    }

    /**
     * Update Post Test.
     */
    public function updatePostTest(Request $request, $id)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'waktu_pengerjaan' => 'required|integer|min:1',
            'ulang_post_test'  => 'required|integer|min:1',
            'questions'        => 'required|array|min:1',
            'questions.*.soal' => 'required|string',
            'questions.*.options' => 'required|array|min:2|max:5',
            'questions.*.jawaban_benar' => 'required|string',
            'questions.*.status_pilihan' => 'required|boolean',
        ]);

        $postTest = PostTest::findOrFail($id);

        DB::beginTransaction();
        try {
            $postTest->update([
                'judul'            => $request->judul,
                'waktu_pengerjaan' => $request->waktu_pengerjaan,
                'ulang_post_test'  => $request->ulang_post_test,
            ]);

            // Sync Questions: Hapus lama, buat baru (Simple way for quiz updates)
            $postTest->soals()->delete();

            foreach ($request->questions as $index => $q) {
                $jawabanRaw = $q['jawaban_benar'];
                $jawabanConverted = str_replace(
                    ['A', 'B', 'C', 'D', 'E', 'a', 'b', 'c', 'd', 'e'],
                    ['1', '2', '3', '4', '5', '1', '2', '3', '4', '5'],
                    $jawabanRaw
                );

                Soal::create([
                    'post_test_id'   => $postTest->post_test_id,
                    'urutan_soal'    => $index + 1,
                    'status_pilihan' => $q['status_pilihan'],
                    'soal'           => $q['soal'],
                    'pilihan_1'      => $q['options'][0] ?? null,
                    'pilihan_2'      => $q['options'][1] ?? null,
                    'pilihan_3'      => $q['options'][2] ?? null,
                    'pilihan_4'      => $q['options'][3] ?? null,
                    'pilihan_5'      => $q['options'][4] ?? null,
                    'jawaban_benar'  => $jawabanConverted,
                    'poin'           => 10,
                ]);
            }

            // Log Aktivitas
            $this->logActivity($request, 'Update', 'post_tests', $id, "Mengupdate kuis: [{$postTest->judul}]");

            DB::commit();
            return back()->with('success', 'Kuis berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui kuis: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Post Test.
     */
    public function destroyPostTest(Request $request, $id)
    {
        $postTest = PostTest::findOrFail($id);
        $judul = $postTest->judul;

        DB::beginTransaction();
        try {
            $postTest->soals()->delete();
            $postTest->delete();

            // Log Aktivitas
            $this->logActivity($request, 'Delete', 'post_tests', $id, "Menghapus kuis: [{$judul}]");

            DB::commit();
            return back()->with('success', 'Kuis berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus kuis: ' . $e->getMessage());
        }
    }

    /**
     * Pindahkan materi ke arsip dengan memundurkan tanggal_selesai ke kemarin.
     */
    public function archive(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        
        // Mundurkan tanggal selesai ke kemarin agar masuk kriteria arsip (< now)
        $materi->update([
            'tanggal_selesai' => now()->subDay()->format('Y-m-d')
        ]);

        // Log Aktivitas
        $this->logActivity($request, 'Update', 'materis', $id, "Memindahkan folder [{$materi->judul}] ke arsip (Manual via Date Change)");

        return redirect()->route('manajemen-pelatihan')->with('success', 'Pelatihan berhasil diarsipkan.');
    }

    /**
     * Download file sub-materi dan catat log.
     */
    // public function downloadSubMateri(Request $request, $id)
    // {
    //     $sub = SubMateri::findOrFail($id);
    //     $filePath = $sub->file_materi;
    //     $judul = $sub->judul;

    //     if (!$filePath || !Storage::disk('public')->exists($filePath)) {
    //         return back()->with('error', 'File tidak ditemukan di server.');
    //     }

    //     // Log Aktivitas
    //     $this->logActivity($request, 'Download', 'sub_materis', $id, "Mengunduh file materi: [{$judul}]");

    //     return Storage::disk('public')->download($filePath);
    // }

    /**
     * Halaman Arsip: Menampilkan materi yang sudah melewati tanggal_selesai.
     */
    public function archiveIndex(Request $request)
    {
        $search = $request->input('search');
        $today = now()->startOfDay();
        
        // Automated Archive Logic: Display folders where tanggal_selesai < today
        $query = Materi::where('tanggal_selesai', '<', $today)->with('kategori');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('subjudul', 'LIKE', "%{$search}%");
            });
        }

        $materis = $query->latest()->paginate(12)->withQueryString();

        return view('SuperAdmin_Views.arsip-pelatihan', compact('materis', 'search'));
    }

    /**
     * Pulihkan materi dari arsip dengan memperpanjang tanggal_selesai (default +7 hari).
     */
    public function unarchive(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        
        // Perpanjang tanggal selesai agar aktif kembali (>= now)
        $materi->update([
            'tanggal_selesai' => now()->addDays(7)->format('Y-m-d')
        ]);

        // Log Aktivitas
        $this->logActivity($request, 'Update', 'materis', $id, "Memulihkan folder [{$materi->judul}] dari arsip (Aktif kembali selama 7 hari ke depan)");

        return redirect()->route('pelatihan.arsip')->with('success', 'Pelatihan berhasil dipulihkan dari arsip (Aktif 7 hari ke depan).');
    }

    /**
     * Hapus permanen dari arsip.
     */
    public function destroyFromArchive(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        $judul = $materi->judul;
        
        DB::beginTransaction();
        try {
            // Hapus file thumbnail
            if ($materi->image_path) {
                Storage::disk('public')->delete($materi->image_path);
            }

            // Hapus file sub_materi
            foreach ($materi->subMateris as $subMateri) {
                if ($subMateri->file_materi) {
                    Storage::disk('public')->delete($subMateri->file_materi);
                }
                $subMateri->delete();
            }

            // Hapus post_test
            foreach ($materi->postTests as $postTest) {
                $postTest->soals()->delete();
                $postTest->delete();
            }

            $materi->unitKerjas()->detach();
            $materi->jenisTenagas()->detach();

            $materi->forceDelete();

            // Log Aktivitas
            $this->logActivity($request, 'Delete', 'materis', $id, "Menghapus folder [{$judul}] dari arsip");

            DB::commit();
            return redirect()->route('pelatihan.arsip')->with('success', 'Pelatihan berhasil dihapus permanen dari arsip.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Private helper for logging.
     */
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
