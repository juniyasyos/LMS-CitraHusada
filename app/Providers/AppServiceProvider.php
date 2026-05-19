<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('view-api-docs', function (User $user) {
            return $user->role_id === 1;
        });

        \Illuminate\Support\Facades\Event::listen(
            \Spatie\Backup\Events\BackupWasSuccessful::class,
            \App\Listeners\BackupSuccessfulListener::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \Spatie\Backup\Events\BackupHasFailed::class,
            \App\Listeners\BackupFailedListener::class
        );
    }
}
