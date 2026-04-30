<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'payment_method',
        'amount',
        'payment_proof',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount'  => 'decimal:2',
    ];

    /**
     * Auto-generate invoice_number saat creating
     */
    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (empty($payment->invoice_number)) {
                $payment->invoice_number = 'INV-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(Str::random(6));
            }
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}