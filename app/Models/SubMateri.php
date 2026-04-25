<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMateri extends Model
{
    use HasFactory;

    protected $primaryKey = 'sub_materi_id';

    protected $fillable = [
        'materi_id',
        'judul',
        'deskripsi',
        'file_materi',
        'urutan_sub_materi',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }
}
