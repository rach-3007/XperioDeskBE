<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabinAndConferenceRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'layout_entity_id',
        'type',
    ];

    public function layoutEntity()
    {
        return $this->belongsTo(LayoutEntity::class);
    }
}

