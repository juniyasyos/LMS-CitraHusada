<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Materi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Menampilkan halaman View utama
     */
    public function index()
    {
        return view('SuperAdmin_Views.manajemen-kategori');
    }

    /**
     * API: Mengambil data kategori, statistik, dan mendukung pencarian
     */
    public function getKategoriData(Request $request)
    {
        $search = $request->input('search');

        $categories = Kategori::withCount('materis')
            ->when($search, function ($query, $search) {
                return $query->where('nama_kategori', 'like', "%{$search}%");
            })
            ->latest('kategori_id')
            ->paginate(6);

        return response()->json([
            'categories' => $categories,
            'stats' => [
                'total_kategori' => Kategori::count(),
                'total_pelatihan' => Materi::count(),
            ]
        ]);
    }

    /**
     * API: Simpan Kategori Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = Kategori::create($request->only(['nama_kategori', 'keterangan']));

        $this->logActivity($request, 'Create', 'kategoris', $kategori->kategori_id, "Menambah kategori pelatihan baru: [{$kategori->nama_kategori}]");

        return response()->json([
            'status' => 'success', 
            'message' => 'Kategori berhasil ditambahkan']);
    }

    /**
     * API: Update Kategori
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $kategori->update($request->only(['nama_kategori', 'keterangan']));

        $this->logActivity($request, 'Update', 'kategoris', $id, "Memperbarui data kategori: [{$kategori->nama_kategori}]");

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui'
            ]);
    }

    /**
     * API: Hapus Kategori
     */
    public function destroy(Request $request, $id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $nama = $kategori->nama_kategori;
        $kategori->delete();

        $this->logActivity($request, 'Delete', 'kategoris', $id, "Menghapus kategori: [{$nama}]");

        return response()->json(['status' => 'success', 'message' => 'Kategori berhasil dihapus']);
    }

    /**
     * Helper: Pencatatan Log Aktivitas
     */
    private function logActivity(Request $request, $tipe, $tabel, $subjectId, $perubahan)
    {
        try {
            LogAktivitas::create([
                'user_id'    => auth()->id(),
                'tipe'       => $tipe,
                'tabel'      => $tabel,
                'subject_id' => $subjectId,
                'perubahan'  => $perubahan,
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Log Aktivitas Gagal: ' . $e->getMessage());
        }
    }
}