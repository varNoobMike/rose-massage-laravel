<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_ACTIVE,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
        self::STATUS_REJECTED,
    ];

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


    // Client (user who booked)
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

    // Each booking have one review
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // A booking can have multiple payment records (attempts, partials, or refunds)
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Helper accessor to easily see if the booking has a successful payment attached
    public function isPaid(): bool
    {
        return $this->payments()->where('status', 'successful')->exists();
    }
}
