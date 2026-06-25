<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            // $redirectUrl = ($user->hasRole('super_admin')) ? '/beranda-superadmin' : (($user->hasRole('admin')) ? '/beranda-admin' : '/pembelajaran');
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
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/pembelajaran');
        }

        return back()->withErrors(["nip" => "Nomor induk atau kata sandi tidak cocok."]);
    }

    /**
     * Process login attempt for API.
     */
    public function loginApi(Request $request)
    {
        try {
            $credentials = $request->validate([
                'nip' => 'required|string',
                'password' => 'required|string',
            ]);

            $remember = $request->boolean('remember'); // 🔥 ini pengganti remember_token

            $user = \App\Models\User::where('nip', $credentials['nip'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User dengan NIP tersebut tidak ditemukan',
                    'data' => null
                ], 401);
            }

            if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah untuk user dengan NIP ini',
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
            $redirectUrl = ($user->hasRole('super_admin')) ? '/beranda-superadmin' : '/pembelajaran';

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
        $traceId = (string) Str::uuid();

        try {
            $user = $request->user();
            $reason = $request->input('reason', 'User initiated logout via API');

            $oldSessionId = $request->hasSession()
                ? $request->session()->getId()
                : null;

            Log::info('Local API Logout Started', [
                'trace_id' => $traceId,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),

                'user_id' => $user?->id,
                'web_user_id' => Auth::guard('web')->id(),

                'has_session' => $request->hasSession(),
                'session_id' => $oldSessionId,

                'has_bearer_token' => filled($request->bearerToken()),
                'reason' => $reason,
            ]);

            /**
             * Jangan langsung:
             * $request->user()->currentAccessToken()->delete();
             *
             * Karena currentAccessToken() bisa null kalau user login via session/web SSO.
             */
            $tokenDeleted = false;
            $tokenId = null;

            if ($user && method_exists($user, 'currentAccessToken')) {
                $currentAccessToken = $user->currentAccessToken();

                if ($currentAccessToken) {
                    $tokenId = $currentAccessToken->id ?? null;

                    if (method_exists($currentAccessToken, 'delete')) {
                        $currentAccessToken->delete();
                        $tokenDeleted = true;
                    }
                }
            }

            Log::info('Local API Logout Token Check Completed', [
                'trace_id' => $traceId,
                'user_id' => $user?->id,
                'has_current_access_token' => $tokenId !== null,
                'token_id' => $tokenId,
                'token_deleted' => $tokenDeleted,
                'note' => $tokenDeleted
                    ? 'Sanctum token deleted'
                    : 'No Sanctum token deleted, probably session/web SSO logout',
            ]);

            /**
             * PENTING:
             * Jika IAM aktif, kita JANGAN logout session web di sini!
             * Biarkan session web tetap hidup agar saat frontend melakukan form submit POST /logout (Web),
             * request tersebut tidak dicegat oleh middleware auth yang menyebabkan redirect ke halaman SSO Login (auto-login loop).
             */
            if (!config('iam.enabled')) {
                Auth::guard('web')->logout();

                Log::info('Local API Logout Web Guard Completed', [
                    'trace_id' => $traceId,
                    'previous_user_id' => $user?->id,
                    'old_session_id' => $oldSessionId,
                ]);

                if ($request->hasSession()) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    Log::info('Local API Logout Session Invalidated', [
                        'trace_id' => $traceId,
                        'old_session_id' => $oldSessionId,
                        'new_session_id' => $request->session()->getId(),
                    ]);
                } else {
                    Log::warning('Local API Logout No Session Found', [
                        'trace_id' => $traceId,
                        'user_id' => $user?->id,
                    ]);
                }
            } else {
                Log::info('Local API Logout Skipping Web Session Invalidation', [
                    'trace_id' => $traceId,
                    'reason' => 'IAM is enabled, waiting for web logout redirect to SSO.'
                ]);
            }

            Log::info('Local API Logout Completed', [
                'trace_id' => $traceId,
                'previous_user_id' => $user?->id,
                'token_deleted' => $tokenDeleted,
                'old_session_id' => $oldSessionId,
                'new_session_id' => $request->hasSession()
                    ? $request->session()->getId()
                    : null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
                'data' => [
                    'trace_id' => $traceId,
                ],
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Local API Logout Failed', [
                'trace_id' => $traceId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),

                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),

                'user_id' => $request->user()?->id,
                'web_user_id' => Auth::guard('web')->id(),

                'has_session' => $request->hasSession(),
                'session_id' => $request->hasSession()
                    ? $request->session()->getId()
                    : null,

                'has_bearer_token' => filled($request->bearerToken()),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan logout',
                'data' => [
                    'trace_id' => $traceId,
                ],
            ], 500);
        }
    }
    /**
     * Logout user (Web-based).
     */
    public function logout(Request $request)
    {
        $reason = $request->input('reason', 'User initiated logout via Web');
        \Illuminate\Support\Facades\Log::info('Local Web Logout Initiated', [
            'user_id' => Auth::id(),
            'session_id' => $request->session()->getId(),
            'reason' => $reason,
        ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
