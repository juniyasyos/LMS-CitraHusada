<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class PembelajaranController extends Controller
{
    public function getProfile()
    {
        $user = User::with(['unitKerja', 'jenisTenaga'])
            ->withSum(['sertifikatEksternals as jpl_eksternal' => function ($q) {
                $q->where('status', 'Disetujui');
            }], 'jpl')
            ->where('user_id', Auth::id())
            ->first();

        // dd($user);
         Log::info('User Profile:', [
        'nama' => $user->nama,
        'unit_kerja' => $user->unitKerja->unit_kerja ?? null,
        'jenis_tenaga' => $user->jenisTenaga->jenis_tenaga ?? null
    ]);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
