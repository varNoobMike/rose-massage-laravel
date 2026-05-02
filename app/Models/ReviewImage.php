<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Review;

class ReviewImage extends Model
{
    protected $fillable = [
        'review_id',
        'path',
    ];

    // 🔗 belongs to review
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}