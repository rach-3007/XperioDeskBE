<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model\SoftDeletes;

class DU extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'du';

    protected $fillable = [
        'du_name',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'du_module_access');
    }
}
