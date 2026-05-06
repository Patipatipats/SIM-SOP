<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopVersion extends Model
{
    protected $table = 'sop_versions';

    protected $fillable = [
        'sop_id',
        'versi',
        'file_path',
        'catatan_revisi',
        'tanggal_berlaku',
        'tanggal_expired',
        'status',
        'approved_by',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'tanggal_expired' => 'date',
    ];    

    public function sop()
    {
        return $this->belongsTo(Sop::class);
    }

    public function approvals()
    {
        return $this->hasMany(SopApproval::class,'version_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function downloadLogs()
    {
        return $this->hasMany(SopDownloadLog::class, 'version_id');
    }    
}