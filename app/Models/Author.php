<?php

namespace App\Models;

use App\Models\Eloquent\Model;

class Author extends Model
{
    protected $connection = 'manga_sql';

    protected $table = 'authors';

    protected $fillable = [
        'name'
    ];

    protected $hidden = ['pivot', 'deleted_at'];
}
