<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChapterThumbnails extends Model
{
    use SoftDeletes;

    protected $connection = 'manga_sql';

    protected $table = 'chapter_thumbnails';

    protected $fillable = [
        'chapter_id',
        'thumbnail_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'thumbnail_url' => 'string',
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
