<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $primaryKey = 'progress_id';

    protected $fillable = [
        'user_id',
        'materi_id',
        'urutan_selesai',
        'skor_total',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }

    public function skorUsers()
    {
        return $this->hasMany(SkorUser::class, 'progress_id', 'progress_id');
    }
}
