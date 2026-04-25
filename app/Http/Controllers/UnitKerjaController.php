<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\JenisTenaga;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    /**
     * Menampilkan daftar unit kerja atau jenis tenaga berdasarkan filter.
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'unit'); // Default to 'unit'
        $search = $request->query('search');

        if ($type === 'tenaga') {
            $query = JenisTenaga::withCount('users');
            if ($search) {
                $query->where('jenis_tenaga', 'like', '%' . $search . '%');
            }
            $data = $query->latest('jenis_tenaga_id')->paginate(10)->withQueryString();
            $total = JenisTenaga::count();
            $title = "Daftar Jenis Tenaga";
        } else {
            $query = UnitKerja::withCount('users');
            if ($search) {
                $query->where('unit_kerja', 'like', '%' . $search . '%');
            }
            $data = $query->latest('unit_kerja_id')->paginate(10)->withQueryString();
            $total = UnitKerja::count();
            $title = "Daftar Unit Kerja";
        }

        return view('SuperAdmin_Views.manajemen-unit-kerja', compact('data', 'total', 'type', 'title'));
    }

    public function store(Request $request)
    {
        $type = $request->input('type');
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($type === 'tenaga') {
            $record = JenisTenaga::create([
                'jenis_tenaga' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
            $this->logActivity($request, 'Create', 'jenis_tenagas', $record->jenis_tenaga_id, "Menambah jenis tenaga baru: " . $request->nama);
        } else {
            $record = UnitKerja::create([
                'unit_kerja' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
            $this->logActivity($request, 'Create', 'unit_kerjas', $record->unit_kerja_id, "Menambah unit kerja baru: " . $request->nama);
        }

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $type = $request->input('type');
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($type === 'tenaga') {
            $record = JenisTenaga::findOrFail($id);
            $record->update([
                'jenis_tenaga' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
            $this->logActivity($request, 'Update', 'jenis_tenagas', $id, "Memperbarui jenis tenaga: " . $request->nama);
        } else {
            $record = UnitKerja::findOrFail($id);
            $record->update([
                'unit_kerja' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
            $this->logActivity($request, 'Update', 'unit_kerjas', $id, "Memperbarui unit kerja: " . $request->nama);
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->input('type');
        
        if ($type === 'tenaga') {
            $record = JenisTenaga::findOrFail($id);
            $nama = $record->jenis_tenaga;
            $record->delete();
            $this->logActivity($request, 'Delete', 'jenis_tenagas', $id, "Menghapus jenis tenaga: " . $nama);
        } else {
            $record = UnitKerja::findOrFail($id);
            $nama = $record->unit_kerja;
            $record->delete();
            $this->logActivity($request, 'Delete', 'unit_kerjas', $id, "Menghapus unit kerja: " . $nama);
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
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
