<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin',
        'destination',
        'departure_time',
        'quota',
        'price',
        'vehicle_type',
        'description',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'price'          => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Label kendaraan
     */
    public function getVehicleLabelAttribute(): string
    {
        return match ($this->vehicle_type) {
            'bus'     => 'Bus',
            'minivan' => 'Minivan',
            'car'     => 'Mobil',
            default   => 'Bus',
        };
    }

    /**
     * Icon kendaraan (Font Awesome class)
     */
    public function getVehicleIconAttribute(): string
    {
        return match ($this->vehicle_type) {
            'bus'     => 'fa-bus',
            'minivan' => 'fa-shuttle-van',
            'car'     => 'fa-car',
            default   => 'fa-bus',
        };
    }
}