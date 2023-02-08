<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $connection = 'manga_sql';

    protected $table = 'categories';

    protected $fillable = [
        'title',
        'domain_id',
        'slug',
        'is_active',
        'total_manga',
        'image',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $hidden = [
        'domain_id', 'deleted_at', 'pivot'
    ];
}
