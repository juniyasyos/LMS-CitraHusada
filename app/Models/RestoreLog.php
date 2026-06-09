<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestoreLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'backup_file',
        'restored_by',
        'restore_started_at',
        'restore_finished_at',
        'status',
        'message',
        'pre_restore_backup',
    ];

    protected $casts = [
        'restore_started_at' => 'datetime',
        'restore_finished_at' => 'datetime',
    ];

    /**
     * Relasi ke user yang melakukan restore.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'restored_by', 'user_id');
    }
}
