<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination',
        'departure_time',
        'quota',
        'price'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}