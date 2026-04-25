<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkorUser extends Model
{
    use HasFactory;

    protected $primaryKey = 'skor_id';

    protected $fillable = [
        'progress_id',
        'post_test_id',
        'skor',
        'waktu_pengerjaan',
        'waktu_mulai_pengerjaan',
        'waktu_selesai_pengerjaan',
    ];

    public function progress()
    {
        return $this->belongsTo(UserProgress::class, 'progress_id', 'progress_id');
    }

    public function postTest()
    {
        return $this->belongsTo(PostTest::class, 'post_test_id', 'post_test_id');
    }
}
