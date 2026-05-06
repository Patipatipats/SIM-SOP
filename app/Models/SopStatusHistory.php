<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopStatusHistory extends Model
{
    protected $table = 'sop_status_history';

    const UPDATED_AT = null;

    protected $fillable = [
        'sop_id',
        'status_lama',
        'status_baru',
        'changed_by',
        'catatan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function sop()
    {
        return $this->belongsTo(Sop::class, 'sop_id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}