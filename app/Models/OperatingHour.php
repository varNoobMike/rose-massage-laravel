<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperatingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'spa_id',
        'day_of_week',
        'day_order',
        'start_time',
        'end_time',
        'is_closed'
    ];

    

    public function spa()
    {
        return $this->belongsTo(Spa::class);
    }

    
}
