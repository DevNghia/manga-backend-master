<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Eloquent\Model;

class NewFeed extends Model
{
    protected $table = 'new_feeds';

    protected $fillable = [
        'user_id',
        'manga_id',
        'chapter_id',
        'comment_id'
    ];

    protected $casts = [
        'manga_id' => 'integer',
        'user_id' => 'integer',
        'chapter_id' => 'integer',
        'comment_id' => 'integer'
    ];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class, 'manga_id', 'id');
    }
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(ChapterThumbnails::class, 'chapter_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
