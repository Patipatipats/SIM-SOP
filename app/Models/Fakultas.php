<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';

    protected $fillable = [
        'nama',
        'singkatan',
        'created_by',
        'updated_by'
    ];

    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class, 'fakultas_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'fakultas_id');
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