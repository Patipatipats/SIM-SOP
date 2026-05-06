<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopComment extends Model
{
    protected $table = 'sop_comments';

    const UPDATED_AT = null;

    protected $fillable = [
        'sop_id',
        'user_id',
        'komentar'
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