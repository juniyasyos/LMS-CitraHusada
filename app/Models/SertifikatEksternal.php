<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatEksternal extends Model
{
    use HasFactory;

    protected $table = 'sertifikat_eksternals';
    protected $primaryKey = 'sertifikat_eksternal_id';

    protected $fillable = [
        'user_id',
        'judul',
        'image_path',
        'jpl',
        'deskripsi',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
