<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model\SoftDeletes;

class Seat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'seat_number',
        'module_id',
        'booked_by_user_id',
        'is_active',
        'layout_entity_id',
        'status',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'booked_by_user_id');
    }

    public function layoutEntity()
    {
        return $this->belongsTo(LayoutEntity::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
