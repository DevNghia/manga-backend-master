<?php

namespace App\Models;

use App\Models\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DomainCrawler extends Model
{
    use SoftDeletes;

    protected $connection = 'manga_sql';

    protected $table = 'domain_crawlers';

    protected $fillable = [
        'domain_name',
        'display_name',
        'is_active',
        'description',
    ];
}
