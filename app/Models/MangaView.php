<?php

namespace App\Models;

use App\Models\Eloquent\Model;

class MangaView extends Model
{
    protected $table = 'manga_views';

    protected $fillable = [
        'manga_id',
        'count_viewer',
    ];

    protected $casts = [
        'manga_id' => 'integer',
        'count_viewer' => 'integer',
    ];
}
