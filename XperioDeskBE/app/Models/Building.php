<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model\SoftDeletes;

class Building extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'building_name',
        'is_active',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}

