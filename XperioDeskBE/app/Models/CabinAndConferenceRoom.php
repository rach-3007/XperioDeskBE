<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CabinAndConferenceRoom extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'layout_entity_id',
        'type',
    ];

    public function layoutEntity()
    {
        return $this->belongsTo(LayoutEntity::class);
    }
}

