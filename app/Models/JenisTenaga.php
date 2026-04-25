<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTenaga extends Model
{
    use HasFactory;

    protected $primaryKey = 'jenis_tenaga_id';

    protected $fillable = ['jenis_tenaga', 'deskripsi'];

    public function users()
    {
        return $this->hasMany(User::class, 'jenis_tenaga_id', 'jenis_tenaga_id');
    }

    public function materis()
    {
        return $this->belongsToMany(Materi::class, 'materi_jenis_tenagas', 'jenis_tenaga_id', 'materi_id');
    }
}
