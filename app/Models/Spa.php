<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\Booking;

class Spa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    
    // 💆 Spa has many services
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // 📅 Spa has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}