<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login page.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // $redirectUrl = ($user->role_id == 1) ? '/beranda-superadmin' : (($user->role_id == 2) ? '/beranda-admin' : '/pembelajaran');
            $redirectUrl = '/pembelajaran';
            return redirect($redirectUrl);
        }
        return view('login');
    }

    /**
     * Process login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/pembelajaran');
        }

        return back()->withErrors(["nik" => "Nomor induk atau kata sandi tidak cocok."]);
    }

    /**
     * Process login attempt for API.
     */
    public function loginApi(Request $request)
    {
        try {
            $credentials = $request->validate([
                'nik' => 'required|string',
                'password' => 'required|string',
            ]);

            $remember = $request->boolean('remember'); // 🔥 ini pengganti remember_token

            $user = \App\Models\User::where('nik', $credentials['nik'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User dengan NIK tersebut tidak ditemukan',
                    'data' => null
                ], 401);
            }

            if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah untuk user dengan NIK ini',
                    'data' => null
                ], 401);
            }

            // Menghapus token lama (optional)
            $user->tokens()->delete();

            // Membuat token baru
            $token = $user->createToken('auth_token')->plainTextToken;

            // SINKRONISASI HYBRID: Buat session Laravel dari API Login agar Frontend Blade bisa diakses
            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Mengatur redirect berdasarkan role! Mencegah user Karyawan dilempar ke Admin dan 403 Forbidden
            $redirectUrl = ($user->role_id == 1) ? '/beranda-superadmin' : '/pembelajaran';

            // Mengatur expiry logic manual (simulasi "remember me")
            $expires_at = $remember
                ? now()->addDays(30)   // ingat saya → 30 hari
                : now()->addHours(5);  // tidak → 2 jam

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'expires_at' => $expires_at,
                    'redirect' => $redirectUrl
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan login: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Logout user for API.
     */
    public function logoutApi(Request $request)
    {
        try {
            // Bersihkan token Sanctum
            $request->user()->currentAccessToken()->delete();

            // Bersihkan Session Web agar tidak ada bocoran state
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan logout',
                'data' => null
            ], 500);
        }
    }
    /**
     * Logout user (Web-based).
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
