<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'gateway_fee',
        'payment_method',
        'status',
        'transaction_id',
        'gateway_response',
        'reference_number',
        'receipt_path',
        'refund_reference'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'amount' => 'float',
        'gateway_fee' => 'float',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
