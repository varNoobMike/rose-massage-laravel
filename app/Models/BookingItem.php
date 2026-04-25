<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'service_id',
        'therapist_id',
        'service_name',
        'service_duration_minutes',
        'service_price',
        'notes',
    ];


    // 🔗 Parent booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // 💆 Service (master service table)
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // 👩‍⚕️ Assigned therapist for this service
    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }
}
