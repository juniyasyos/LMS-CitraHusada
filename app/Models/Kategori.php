<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];

    public function materis()
    {
        return $this->hasMany(Materi::class, 'kategori_id', 'kategori_id');
    }
}
