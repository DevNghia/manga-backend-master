<?php

namespace App\Models;

use App\Models\Eloquent\Model;

class ChapterViewer extends Model
{
    protected $table = 'chapter_viewer';

    protected $fillable = [
        'manga_id',
        'chapter_id',
        'count_viewer',
    ];

    protected $casts = [
        'manga_id' => 'integer',
        'chapter_id' => 'integer',
        'count_viewer' => 'integer',
    ];
}
