<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

// class Layout extends Model
// {
//     use HasFactory;
//     use SoftDeletes;

//     protected $fillable = [
//         'module_id',
//         'name',
//     ];

//     public function module()
//     {
//         return $this->belongsTo(Module::class);
//     }

//     public function layoutEntities()
//     {
//         return $this->hasMany(LayoutEntity::class);
//     }
// }
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_name',
        'access_dus',
        'layout_entities',
        'entry_point',
    ];

    protected $casts = [
        'access_dus' => 'array',
        'layout_entities' => 'array',
        'entry_point' => 'array',
    ];
    public function layoutEntities()
    {
        return $this->hasMany(LayoutEntity::class);
    }
}
