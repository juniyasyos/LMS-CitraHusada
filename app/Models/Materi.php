<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Materi extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'materi_id';

    protected $fillable = [
        'judul',
        'nama_pemateri',
        'subjudul',
        'deskripsi',
        'image_path',
        'tanggal_upload',
        'tanggal_selesai',
        'jam_pelajaran',
        'kategori_id',
        'nomor_surat',
        'arsip',
        'is_cleaned',
    ];

    protected $appends = ['thumbnail_url'];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * Accessor: Mengembalikan URL lengkap untuk thumbnail,
     * menggunakan disk default (local/public/s3) secara dinamis.
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }
        return Storage::url($this->image_path);
    }

    /**
     * Scope: Hanya mengambil materi yang filenya BELUM dibersihkan (bukan hapus permanen).
     * Digunakan untuk menyembunyikan materi yang sudah dihapus permanen di halaman karyawan dan kelola materi aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_cleaned', false);
    }

    public function scopeAvailable($query)
    {
        $today = Carbon::today();

        return $query->where('is_cleaned', false)
            ->whereDate('tanggal_upload', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $today);
            });
    }

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
