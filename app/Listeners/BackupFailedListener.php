<?php

namespace App\Listeners;

use Spatie\Backup\Events\BackupHasFailed;
use App\Models\BackupLog;

class BackupFailedListener
{
    public function handle(BackupHasFailed $event)
    {
        BackupLog::create([
            'filename' => null,
            'status'   => 'failed',
            'size'     => 0,
            'message'  => $event->exception->getMessage(),
        ]);
    }
}
