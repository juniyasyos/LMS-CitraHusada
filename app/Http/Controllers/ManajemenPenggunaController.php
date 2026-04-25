<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\JenisTenaga;
use App\Models\Role;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManajemenPenggunaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $showAll = $request->has('all');
        $search = $request->input('search');

        $query = User::with(['jenisTenaga', 'unitKerja', 'role']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', '%' . $search . '%')
                    ->orWhere('nik', 'LIKE', '%' . $search . '%');
            });
        }

        if ($showAll) {
            $users = $query->latest('user_id')->get();
        } else {
            $users = $query->latest('user_id')->paginate($perPage)->withQueryString();
        }

        // Needed for edit modal dropdowns
        $unit_kerjas = UnitKerja::all();
        $jenis_tenagas = JenisTenaga::all();
        $roles = Role::all();

        return view('SuperAdmin_Views.manajemen-pengguna', compact('users', 'unit_kerjas', 'jenis_tenagas', 'roles'));
    }

    public function create()
    {
        $unit_kerjas = UnitKerja::all();
        $jenis_tenagas = JenisTenaga::all();
        $roles = Role::all();

        return view('SuperAdmin_Views.tambah-peran', compact('unit_kerjas', 'jenis_tenagas', 'roles'));
    }

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

        $status = $request->has('status') && $request->status == 'on' ? 'Aktif' : 'Tidak Aktif';

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

        // Log Activity
        $this->logActivity($request, 'Create', 'users', $user->user_id, "Menambah pengguna baru: " . $user->nama);

        return redirect()->route('manajemen-pengguna')->with('success', 'Pengguna berhasil ditambahkan!');
    }

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

        // Log Activity
        $this->logActivity($request, 'Update', 'users', $user->user_id, "Memperbarui data pengguna: " . $user->nama);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $nama = $user->nama;

        $user->delete();

        // Log Activity
        $this->logActivity($request, 'Delete', 'users', $id, "Menghapus pengguna: " . $nama);

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus!');
    }

    public function impersonate(Request $request, $id)
    {
        $originalId = Auth::id();
        $targetUser = User::findOrFail($id);

        // Prevent self-impersonation
        if ($originalId == $id) {
            return redirect()->back()->with('error', 'Tidak dapat melakukan impersonasi ke akun sendiri.');
        }

        // Store original admin ID in session
        session()->put('impersonate_by', $originalId);

        // Login as target user
        Auth::login($targetUser);

        return redirect()->route('pembelajaran')->with('success', 'Anda sekarang masuk sebagai ' . $targetUser->nama);
    }

    public function stopImpersonating()
    {
        $originalId = session()->pull('impersonate_by');

        if ($originalId) {
            $adminUser = User::find($originalId);
            if ($adminUser) {
                Auth::login($adminUser);
                return redirect()->route('manajemen-pengguna')->with('success', 'Kembali ke akun Admin.');
            }
        }

        return redirect('/');
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
