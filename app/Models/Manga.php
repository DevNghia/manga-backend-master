<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manga extends Model
{
    use SoftDeletes;
    protected $connection = 'manga_sql';

    protected $table = 'manga';

    protected $fillable = [
        'image',
        'is_active',
        'release_at',
        'rank',
        'chapter_count',
        'type',
        'title',
        'description',
        'domain_id',
        'slug'
    ];

    protected $hidden = [
        'domain_id', 'deleted_at'
    ];

    protected $casts = [
        'image' => 'string',
        'is_active' => 'boolean',
        'release_at' => 'integer',
        'rank' => 'integer',
        'chapter_count' => 'integer',
        'type' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    protected $appends = [
        'manga_id'
    ];

    public function getMangaIdAttribute()
    {
        return $this->slug . '---' . $this->id;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class,  MangaCategory::class, 'manga_id', 'category_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'manga_id', 'id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'manga_authors', 'manga_id', 'author_id');
    }

    public function viewer(): BelongsTo
    {
        return $this->BelongsTo(MangaView::class, 'manga_id', 'id');
    }
}
