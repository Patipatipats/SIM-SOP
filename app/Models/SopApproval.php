<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopApproval extends Model
{
    protected $table = 'sop_approvals';

    protected $fillable = [
        'version_id',
        'user_id',
        'level_approval',
        'approval_order',
        'status',
        'catatan',
        'approved_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'level_approval' => 'integer',
        'approval_order' => 'integer',
    ];

    public function version()
    {
        return $this->belongsTo(SopVersion::class,'version_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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