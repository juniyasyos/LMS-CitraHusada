<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role_id != $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden Access.'], 403);
            }
            abort(403, 'Akses ditolak: Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
