<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $primaryKey = 'unit_kerja_id';

    protected $fillable = ['unit_name', 'deskripsi'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_unit_kerja', 'unit_kerja_id', 'user_id');
    }

    public function materiUnitKerjas()
    {
        return $this->hasMany(MateriUnitKerja::class, 'unit_kerja_id', 'unit_kerja_id');
    }
}
