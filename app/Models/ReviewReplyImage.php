<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReplyImage extends Model
{
    protected $fillable = [
        'review_reply_id',
        'path',
    ];

    // 🔗 belongs to a reply
    public function reply()
    {
        return $this->belongsTo(ReviewReply::class, 'review_reply_id');
    }
}
