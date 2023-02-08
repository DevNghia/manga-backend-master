<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    protected $table = 'downloads';

    protected $fillable = [
        'manga_id',
        'chapter_id',
        'user_id',
        'counter',
    ];

    protected $casts = [
        'thumbnail_count' => 'integer',
        'chapter_id' => 'integer',
        'user_id' => 'integer',
        'manga_id' => 'integer',
    ];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class, 'manga_id', 'id');
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    }
}
