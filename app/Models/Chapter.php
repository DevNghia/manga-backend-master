<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use SoftDeletes;

    protected $connection = 'manga_sql';

    protected $table = 'chapters';

    protected $fillable = [
        'manga_id',
        'name',
        'is_active',
        'thumbnail_count',
    ];

    protected $casts = [
        'name' => 'string',
        'is_active' => 'boolean',
        'thumbnail_count' => 'integer',
        'manga_id' => 'integer',
    ];

    public function thumbnails(): HasMany
    {
        return $this->hasMany(ChapterThumbnails::class, 'chapter_id', 'id');
    }

    protected $hidden = [
        'deleted_at'
    ];
}
