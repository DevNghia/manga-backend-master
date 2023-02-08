<?php

namespace App\Models;

use App\Models\Eloquent\Model;

class UserReview extends Model
{
    protected $table = 'user_reviews';

    protected $fillable = [
        'manga_id',
        'review',
        'score',
        'reply_id',
    ];

    protected $casts = [
        'manga_id' => 'integer',
        'user_id' => 'integer',
        'reply_id' => 'integer',
    ];
}
