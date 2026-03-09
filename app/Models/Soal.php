<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $primaryKey = 'soal_id';

    protected $fillable = [
        'urutan_post_test',
        'post_test_id',
        'status_pilihan',
        'soal',
        'pilihan_1',
        'pilihan_2',
        'pilihan_3',
        'pilihan_4',
        'pilihan_5',
        'jawaban_benar',
    ];

    public function postTest()
    {
        return $this->belongsTo(PostTest::class, 'post_test_id', 'post_test_id');
    }
}
