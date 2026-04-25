<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materi extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'materi_id';

    protected $fillable = [
        'judul',
        'subjudul',
        'deskripsi',
        'image_path',
        'tanggal_upload',
        'tanggal_selesai',
        'jam_pelajaran',
        'kategori_id',
        'arsip',
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function subMateris()
    {
        return $this->hasMany(SubMateri::class, 'materi_id', 'materi_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    public function unitKerjas()
    {
        return $this->belongsToMany(UnitKerja::class, 'materi_unit_kerjas', 'materi_id', 'unit_kerja_id');
    }

    public function jenisTenagas()
    {
        return $this->belongsToMany(JenisTenaga::class, 'materi_jenis_tenagas', 'materi_id', 'jenis_tenaga_id');
    }

    public function progresses()
    {
        return $this->hasMany(UserProgress::class, 'materi_id', 'materi_id');
    }

    public function postTests()
    {
        return $this->hasMany(PostTest::class, 'materi_id', 'materi_id');
    }

    public function materiUnitKerjas()
    {
        return $this->hasMany(MateriUnitKerja::class, 'materi_id', 'materi_id');
    }

    public function materiJenisTenagas()
    {
        return $this->hasMany(MateriJenisTenaga::class, 'materi_id', 'materi_id');
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'materi_id', 'materi_id');
    }
}
