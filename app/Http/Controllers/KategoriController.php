<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Materi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $totalKategori = Kategori::count();
        $totalPelatihan = Materi::count();

        $categories = Kategori::withCount('materis')
            ->when($search, function ($query, $search) {
                return $query->where('nama_kategori', 'like', "%{$search}%");
            })
            ->latest('kategori_id')
            ->paginate(6);

        return view('SuperAdmin_Views.manajemen-kategori', compact('categories', 'totalKategori', 'totalPelatihan', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan,
        ]);

        $this->logActivity($request, 'Insert', 'kategoris', $kategori->kategori_id, "Menambah kategori pelatihan baru: [{$kategori->nama_kategori}]");

        return redirect()->route('manajemen-kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan,
        ]);

        $this->logActivity($request, 'Update', 'kategoris', $id, "Memperbarui data kategori: [{$kategori->nama_kategori}]");

        return redirect()->route('manajemen-kategori')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $nama = $kategori->nama_kategori;
        $kategori->delete();

        $this->logActivity($request, 'Delete', 'kategoris', $id, "Menghapus kategori: [{$nama}]");

        return redirect()->route('manajemen-kategori')->with('success', 'Kategori berhasil dihapus');
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
            \Log::error('Log Aktivitas Gagal: ' . $e->getMessage());
        }
    }
}
