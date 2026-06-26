<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'no.impersonate' => \App\Http\Middleware\PreventImpersonateAdmin::class,
            'require.iam' => \App\Http\Middleware\RequireIam::class,
        ]);

        // Add session middleware to API routes for session-based authentication
        // Use prepend to ensure session middleware runs early in the stack
        $middleware->api(prepend: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
        ]);

        // RequireIam tidak perlu sebagai global middleware.
        // Jika IAM disabled, aplikasi tetap berjalan dengan auth lokal (Sanctum/session).
        // Gunakan middleware 'require.iam' secara eksplisit di route yang memerlukannya.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
