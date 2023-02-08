<?php

namespace App\Models;

use App\Models\Eloquent\Model;

class UserVerifyToken extends Model
{
    protected $table = 'user_verify_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'expired_at',
        'payload',
        'is_verified',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'is_verified' => 'boolean',
        'expired_at' => 'integer',
    ];
}
