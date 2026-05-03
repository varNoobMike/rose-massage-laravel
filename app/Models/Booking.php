<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'spa_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'total_amount',
        'notes',
    ];

    // 👤 Client (user who booked)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Each booking can have multiple items (services booked)
    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    // It belongs to the spa where the booking was made
    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }


}
