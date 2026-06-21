<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\JenisTenaga;
use App\Models\Role;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManajemenPenggunaController extends Controller
{
    /**
     * Menampilkan halaman View utama
     */
    public function index()
    {
        $unit_kerjas = UnitKerja::all();
        $jenis_tenagas = JenisTenaga::all();
        $roles = Role::all();

        return view('SuperAdmin_Views.manajemen-pengguna', compact('unit_kerjas', 'jenis_tenagas', 'roles'));
    }

    /**
     * API: Mengambil data user untuk tabel asinkron
     */
    public function getData(Request $request)
    {
        $search = $request->input('search');

        $query = User::with(['jenisTenaga', 'unitKerja', 'role'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            })
            ->latest('user_id');

        if ($request->input('all') === 'true') {
            return response()->json([
                'data' => $query->get(),
                'links' => []
            ]);
        }

        $perPage = $request->input('per_page', 10);
        return response()->json($query->paginate($perPage));
    }

    /**
     * API: Simpan Pengguna Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:users,nik',
            'password' => 'required|string|min:3',
            'unit_kerja_id' => 'required|exists:unit_kerjas,unit_kerja_id',
            'jenis_tenaga_id' => 'required|exists:jenis_tenagas,jenis_tenaga_id',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        $status = $request->input('status', 'inactive');

        $user = User::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'password' => Hash::make($request->password),
            'total_jpl' => $request->total_jpl ?? 0,
            'unit_kerja_id' => $request->unit_kerja_id,
            'jenis_tenaga_id' => $request->jenis_tenaga_id,
            'role_id' => $request->role_id,
            'status' => $status,
        ]);

        $this->logActivity($request, 'Create', 'users', $user->user_id, "Menambah pengguna baru: [{$user->nama}]");

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil ditambahkan'
        ]);
    }

    /**
     * API: Update Pengguna
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:users,nik,' . $user->user_id . ',user_id',
            'unit_kerja_id' => 'required|exists:unit_kerjas,unit_kerja_id',
            'jenis_tenaga_id' => 'required|exists:jenis_tenagas,jenis_tenaga_id',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        $user->nama = $request->nama;
        $user->nik = $request->nik;
        $user->unit_kerja_id = $request->unit_kerja_id;
        $user->jenis_tenaga_id = $request->jenis_tenaga_id;
        $user->role_id = $request->role_id;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $this->logActivity($request, 'Update', 'users', $id, "Memperbarui data pengguna: [{$user->nama}]");

        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil diperbarui'
        ]);
    }

    /**
     * API: Hapus Pengguna
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $nama = $user->nama;
        $user->delete();

        $this->logActivity($request, 'Delete', 'users', $id, "Menghapus pengguna: [{$nama}]");

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil dihapus'
        ]);
    }

    /**
     * WEB: Impersonate (Masuk sebagai user lain)
     */
    public function impersonate(Request $request, $id)
    {
        $originalId = Auth::id();
        $targetUser = User::findOrFail($id);

        if ($originalId == $id) {
            return back()->with('error', 'Tidak dapat melakukan impersonasi ke akun sendiri.');
        }

        session()->put('impersonate_by', $originalId);
        Auth::login($targetUser);

        return redirect()->route('pembelajaran');
    }

    /**
     * WEB: Berhenti Impersonate
     */
    public function stopImpersonating()
    {
        $originalId = session()->pull('impersonate_by');

        if ($originalId) {
            $adminUser = User::find($originalId);
            if ($adminUser) {
                Auth::login($adminUser);
                return redirect()->route('manajemen-pengguna');
            }
        }

        return redirect('/');
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