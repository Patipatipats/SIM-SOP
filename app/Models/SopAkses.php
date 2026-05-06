<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopAkses extends Model
{
    protected $table = 'sop_akses';

    protected $fillable = [
        'sop_id',
        'unit_kerja_id',
        'role_id',
        'is_public',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function sop()
    {
        return $this->belongsTo(Sop::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
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