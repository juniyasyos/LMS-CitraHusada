<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'is_read',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array', // otomatis json <-> array
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}