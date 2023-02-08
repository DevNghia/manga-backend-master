<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'manga_id',
        'parent_id',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'user_id' => 'integer',
        'manga_id' => 'integer',
        'content' => 'string',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
