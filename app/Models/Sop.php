<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sop extends Model
{
    use SoftDeletes;

    protected $table = 'sop';

    protected $fillable = [
        'kode_sop',
        'judul',
        'kategori_id',
        'unit_kerja_id',
        'deskripsi',
        'status',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function kategori()
    {
        return $this->belongsTo(KategoriSop::class, 'kategori_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function versions()
    {
        return $this->hasMany(SopVersion::class, 'sop_id', 'id');
    }

    public function akses()
    {
        return $this->hasMany(SopAkses::class, 'sop_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'sop_tags',
            'sop_id',
            'tag_id'
        );
    }

    public function comments()
    {
        return $this->hasMany(SopComment::class, 'sop_id');
    }

    public function views()
    {
        return $this->hasMany(SopView::class, 'sop_id');
    }

    public function downloadLogs()
    {
        return $this->hasMany(SopDownloadLog::class, 'sop_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(SopStatusHistory::class, 'sop_id');
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