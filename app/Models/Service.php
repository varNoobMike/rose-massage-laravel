<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'spa_id',
        'name',
        'duration_minutes',
        'price',
        'image',
        'description',
        'status',
    ];

    // 🏢 Spa branch this service belongs to
    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }

    // 🧾 Bookings that include this service
    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // Only active services
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Only inactive services
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}