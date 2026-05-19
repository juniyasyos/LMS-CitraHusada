<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-cleanup: Hapus permanen pelatihan yang sudah di sampah > 30 hari
Schedule::call(function () {
    \App\Http\Controllers\Api\ManajemenPelatihanController::autoCleanTrash();
})->daily()->description('Auto-delete trashed pelatihan older than 30 days');

// Dynamic Backup Schedule
try {
    if (Schema::hasTable('backup_settings')) {
        $setting = \App\Models\BackupSetting::first();
        if ($setting && $setting->is_active) {
            $frequency = strtolower($setting->frequency);
            $time = $setting->execution_time;

            // Map frequency to schedule methods
            if (in_array($frequency, ['daily', 'weekly', 'monthly', 'yearly'])) {
                Schedule::command('backup:run')
                    ->$frequency()
                    ->at($time)
                    ->description('Dynamic backup based on superadmin settings');
            }
        }
    }
} catch (\Exception $e) {
    // Fail silently during bootstrap if DB is not ready
}
