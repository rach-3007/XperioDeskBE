<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutEntity extends Model
{
    use HasFactory;

    protected $fillable = [
        'layout_id',
        'type',
        'x_position',
        'y_position',
        'rotation',
    ];

    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }

    public function seat()
    {
        return $this->hasOne(Seat::class);
    }

    public function cabinAndConferenceRoom()
    {
        return $this->hasOne(CabinAndConferenceRoom::class);
    }
    public function partitions()
    {
        return $this->hasOne(PartitionTable::class, 'layout_entity_id');
    }
}
