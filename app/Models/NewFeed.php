<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Eloquent\Model;

class NewFeed extends Model
{
    protected $table = 'favorites';

    protected $fillable = [
        'user_id',
        'manga_id'
    ];

    protected $casts = [
        'manga_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class, 'manga_id', 'id');
    }
}
