<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama_unit',
        'unit_singkatan',
        'tipe_unit',
        'status_unit',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'status_unit' => 'boolean',
    ];

    public function sops()
    {
        return $this->hasMany(Sop::class, 'unit_kerja_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'unit_kerja_id');
    }

    public function profilPengguna()
    {
        return $this->hasMany(ProfilPengguna::class, 'unit_kerja_id');
    }

    public function sopAkses()
    {
        return $this->hasMany(SopAkses::class, 'unit_kerja_id');
    }
        
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }    
}