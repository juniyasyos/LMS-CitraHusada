<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method \Laravel\Sanctum\NewAccessToken createToken(string $name, array $abilities = ['*'])
 * @property-read \Illuminate\Database\Eloquent\Collection $tokens
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'nama',
        'jenis_tenaga_id',
        'unit_kerja_id',
        'nik',
        'password',
        'role_id',
        'status',
        'total_jpl',
    ];

    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function jenisTenaga()
    {
        return $this->belongsTo(JenisTenaga::class, 'jenis_tenaga_id', 'jenis_tenaga_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id', 'unit_kerja_id');
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'user_id', 'user_id');
    }

    public function progresses()
    {
        return $this->hasMany(UserProgress::class, 'user_id', 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    //  public function skorUsers()
    // {
    //     return $this->hasMany(SkorUser::class, 'user_id', 'user_id');
    // }
}
