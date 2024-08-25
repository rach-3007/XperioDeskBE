<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'name',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function layoutEntities()
    {
        return $this->hasMany(LayoutEntity::class);
    }
}
