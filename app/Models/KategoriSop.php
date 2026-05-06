<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriSop extends Model
{
    use HasFactory;

    protected $table = 'kategori_sop';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // relasi ke SOP
    public function sops()
    {
        return $this->hasMany(Sop::class, 'kategori_id', 'id');
    }

    // user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // user pengubah
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}