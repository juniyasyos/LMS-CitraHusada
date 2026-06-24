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
        'iam_id',
        'nama',
        'jenis_tenaga_id',
        'unit_kerja_id',
        'nip',
        'password',
        'roles',
        'status',
        'total_jpl',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'roles' => 'array',
    ];

    public function hasRole($role)
    {
        $roles = $this->roles ?? [];
        if (is_array($role)) {
            return count(array_intersect($role, $roles)) > 0;
        }
        return in_array($role, $roles);
    }

    public function hasAnyRole(array $roles)
    {
        return $this->hasRole($roles);
    }

    public function syncRoles(array $roles)
    {
        $this->roles = $roles;
        $this->save();
    }

    public function jenisTenaga()
    {
        return $this->belongsTo(JenisTenaga::class, 'jenis_tenaga_id', 'jenis_tenaga_id');
    }

    public function unitKerjas()
    {
        return $this->belongsToMany(UnitKerja::class, 'user_unit_kerja', 'user_id', 'unit_kerja_id');
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'user_id', 'user_id');
    }

    public function sertifikatEksternals()
    {
        return $this->hasMany(SertifikatEksternal::class, 'user_id', 'user_id');
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
