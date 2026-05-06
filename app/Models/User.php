<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'unit_kerja_id',
        'fakultas_id',
        'prodi_id',
        'username',
        'email',
        'password',
        'no_hp',
        'status',
        'last_login',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function profil()
    {
        return $this->hasOne(ProfilPengguna::class);
    }

    public function approvals()
    {
        return $this->hasMany(SopApproval::class);
    }

    public function downloadLogs()
    {
        return $this->hasMany(SopDownloadLog::class);
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }    

    public function comments()
    {
        return $this->hasMany(SopComment::class, 'user_id');
    }

    public function views()
    {
        return $this->hasMany(SopView::class, 'user_id');
    }

    public function tempUsers()
    {
        return $this->hasMany(TempUser::class, 'user_id');
    }

    public function approvedVersions()
    {
        return $this->hasMany(SopVersion::class, 'approved_by');
    }
}