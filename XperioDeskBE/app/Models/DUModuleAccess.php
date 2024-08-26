<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DUModuleAccess extends Model
{
    use HasFactory;

    protected $table = 'du_module_access';

    protected $fillable = [
        'du_id',
        'module_id',
    ];

    public function du()
    {
        return $this->belongsTo(DU::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
