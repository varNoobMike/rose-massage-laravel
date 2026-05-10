<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'rating',
        'comment',
        'status'
    ];

    public const STATUS_PENDING= 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    // User who made the review
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Booking being reviewed
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // review images
    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    // replies
    public function replies()
    {
        return $this->hasMany(ReviewReply::class);
    }
}
