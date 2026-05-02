<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_schedule_id',
        'booking_code',
        'seats',
        'passenger_name',
        'passenger_phone',
        'status',
        'cancelled_at',
        'rating',
        'review',
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
    ];

    /**
     * Auto-generate booking_code saat creating
     */
    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'TRV-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function travelSchedule()
    {
        return $this->belongsTo(TravelSchedule::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Total harga booking
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->seats * ($this->travelSchedule->price ?? 0);
    }

    /**
     * Apakah booking bisa dibatalkan
     */
    public function isCancellable(): bool
    {
        return $this->status === 'pending';
    }
}