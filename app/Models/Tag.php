<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'nama'
    ];

    public function sops()
    {
        return $this->belongsToMany(Sop::class, 'sop_tags', 'tag_id', 'sop_id');
    }
}