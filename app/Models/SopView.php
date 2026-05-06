<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopView extends Model
{
    protected $table = 'sop_views';

    public $timestamps = false;

    protected $fillable = [
        'sop_id',
        'user_id',
        'ip_address',
        'viewed_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function sop()
    {
        return $this->belongsTo(Sop::class, 'sop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }    
}