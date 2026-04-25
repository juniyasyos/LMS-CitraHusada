<?php

namespace App\Listeners;

use Spatie\Backup\Events\BackupWasSuccessful;
use App\Models\BackupLog;
use Illuminate\Support\Facades\Storage;

class BackupSuccessfulListener
{
    public function handle(BackupWasSuccessful $event)
    {
        // Spatie v10: event only has diskName and backupName
        $disk = Storage::disk($event->diskName);
        $backupPath = $event->backupName;

        // Find the newest backup file in the backup directory
        $files = $disk->allFiles($backupPath);
        $newestFile = collect($files)
            ->filter(fn($f) => str_ends_with($f, '.zip'))
            ->sort()
            ->last();

        BackupLog::create([
            'filename' => $newestFile ?? 'N/A',
            'status'   => 'success',
            'size'     => $newestFile ? $disk->size($newestFile) : 0,
            'message'  => 'Backup berhasil pada disk: ' . $event->diskName,
        ]);
    }
}
