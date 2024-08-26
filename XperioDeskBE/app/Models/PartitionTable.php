<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartitionTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'partitions_tables'; // Specify the table name

    protected $fillable = [
        'layout_entity_id',
        'x-position',
        'height',
        'width',
        'y-position',
    ];

    protected $dates = ['deleted_at']; // For soft deletes

    /**
     * Get the layout entity associated with this partition.
     */
    public function layoutEntity()
    {
        return $this->belongsTo(LayoutEntity::class);
    }

}
