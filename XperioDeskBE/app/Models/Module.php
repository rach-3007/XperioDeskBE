<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'module_name',
        'building_id',
        'is_active',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function dus()
    {
        return $this->belongsToMany(DU::class, 'du_module_access');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function layouts()
    {
        return $this->hasMany(Layout::class);
    }
}
