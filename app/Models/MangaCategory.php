<?php

namespace App\Models;

use App\Models\Eloquent\Model;

class MangaCategory extends Model
{
    protected $connection = 'manga_sql';

    protected $table = 'manga_categories';
    protected $fillable = [
        'manga_id',
        'category_id',
    ];
    public $timestamps = false;
}
