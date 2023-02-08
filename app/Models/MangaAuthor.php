<?php

namespace App\Models;

use App\Models\Eloquent\Model;

class MangaAuthor extends Model
{
    protected $connection = 'manga_sql';
    protected $table = 'manga_authors';
    protected $fillable = [
        'manga_id',
        'author_id',
    ];

    public $timestamps = false;
}
