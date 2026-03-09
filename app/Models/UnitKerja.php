<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $primaryKey = 'unit_kerja_id';

    protected $fillable = ['unit_kerja'];

    public function users()
    {
        return $this->hasMany(User::class, 'unit_kerja_id', 'unit_kerja_id');
    }

    public function materiUnitKerjas()
    {
        return $this->hasMany(MateriUnitKerja::class, 'unit_kerja_id', 'unit_kerja_id');
    }
}
