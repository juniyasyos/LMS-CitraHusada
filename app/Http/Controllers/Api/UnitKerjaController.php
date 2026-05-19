<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use App\Models\JenisTenaga;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitKerjaController extends Controller
{
    /**
     * Menampilkan daftar unit kerja atau jenis tenaga.
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'unit'); 
        $search = $request->query('search');

        if ($type === 'tenaga') {
            $query = JenisTenaga::withCount('users');
            if ($search) {
                $query->where('jenis_tenaga', 'like', '%' . $search . '%');
            }
            $data = $query->latest('jenis_tenaga_id')->get();
            $total = JenisTenaga::count();
            $title = "Daftar Jenis Tenaga";
        } else {
            $query = UnitKerja::withCount('users');
            if ($search) {
                $query->where('unit_kerja', 'like', '%' . $search . '%');
            }
            $data = $query->latest('unit_kerja_id')->get();
            $total = UnitKerja::count();
            $title = "Daftar Unit Kerja";
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengambil $title",
            'data' => $data,
            'total' => $total,
            'type' => $type
        ]);
    }

    /**
     * Menyimpan data baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:unit,tenaga',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $type = $request->input('type');

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

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambahkan!',
            'data' => $record
        ]);
    }

    /**
     * Memperbarui data.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:unit,tenaga',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $type = $request->input('type');

        if ($type === 'tenaga') {
            $record = JenisTenaga::find($id);
            if (!$record) return $this->notFoundResponse();
            
            $record->update([
                'jenis_tenaga' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
            $this->logActivity($request, 'Update', 'jenis_tenagas', $id, "Memperbarui jenis tenaga: " . $request->nama);
        } else {
            $record = UnitKerja::find($id);
            if (!$record) return $this->notFoundResponse();

            $record->update([
                'unit_kerja' => $request->nama,
                'deskripsi' => $request->deskripsi,
            ]);
            $this->logActivity($request, 'Update', 'unit_kerjas', $id, "Memperbarui unit kerja: " . $request->nama);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui!',
            'data' => $record
        ]);
    }

    /**
     * Menghapus data.
     */
    public function destroy(Request $request, $id)
    {
        $type = $request->query('type'); // Ambil type dari query params untuk delete

        if ($type === 'tenaga') {
            $record = JenisTenaga::find($id);
            if (!$record) return $this->notFoundResponse();
            
            $nama = $record->jenis_tenaga;
            $record->delete();
            $this->logActivity($request, 'Delete', 'jenis_tenagas', $id, "Menghapus jenis tenaga: " . $nama);
        } else {
            $record = UnitKerja::find($id);
            if (!$record) return $this->notFoundResponse();

            $nama = $record->unit_kerja;
            $record->delete();
            $this->logActivity($request, 'Delete', 'unit_kerjas', $id, "Menghapus unit kerja: " . $nama);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    private function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
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