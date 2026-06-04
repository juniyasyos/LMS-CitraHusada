<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direktur extends Model
{
    protected $primaryKey = 'uid';

    protected $fillable = [
        'nama',
        'jabatan',
        'nik',
        'ttd_path',
    ];
}
