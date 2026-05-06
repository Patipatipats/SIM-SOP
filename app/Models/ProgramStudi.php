<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';

    protected $fillable = [
        'fakultas_id',
        'kode_prodi',
        'nama',
        'singkatan',
        'created_by',
        'updated_by'
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'prodi_id');
    }

    public function profilPengguna()
    {
        return $this->hasMany(ProfilPengguna::class, 'fk_prodi_id');
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