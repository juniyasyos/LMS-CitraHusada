<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTest extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_test_id';

    protected $fillable = [
        'materi_id',
        'urutan_post_test',
        'waktu_pengerjaan',// dalam menit
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }

    public function soal()
    {
        return $this->hasMany(Soal::class, 'post_test_id', 'post_test_id');
    }
}
