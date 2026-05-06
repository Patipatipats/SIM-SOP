<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopDownloadLog extends Model
{
    protected $table = 'sop_download_logs';

    const UPDATED_AT = null;

    protected $fillable = [
        'sop_id',
        'version_id',
        'user_id',
        'ip_address',
        'user_agent',
        'downloaded_at'
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function sop()
    {
        return $this->belongsTo(Sop::class, 'sop_id');
    }

    public function version()
    {
        return $this->belongsTo(SopVersion::class, 'version_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}