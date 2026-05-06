<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopTag extends Model
{
    protected $table = 'sop_tags';
    protected $primaryKey = null;
    
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'sop_id',
        'tag_id'
    ];
}