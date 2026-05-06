<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPengguna extends Model
{
    protected $table = 'profil_pengguna';

    protected $fillable = [
        'user_id',
        'nama',
        'nim_nip',
        'nidn',
        'status_pengguna',
        'jenis_pengguna',
        'is_aktif',
        'fk_prodi_id',
        'unit_kerja_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'fk_prodi_id');
    }    

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class,'unit_kerja_id');
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