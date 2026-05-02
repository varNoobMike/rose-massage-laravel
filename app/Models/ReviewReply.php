<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    protected $fillable = [
        'review_id',
        'user_id',
        'parent_id',
        'message',
    ];

    // 🔗 belongs to review
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    // 🔗 author
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔁 parent reply
    public function parent()
    {
        return $this->belongsTo(ReviewReply::class, 'parent_id');
    }

    // 🔁 child replies
    public function children()
    {
        return $this->hasMany(ReviewReply::class, 'parent_id');
    }

    // 🖼️ images
    public function images()
    {
        return $this->hasMany(ReviewReplyImage::class);
    }
    
}
