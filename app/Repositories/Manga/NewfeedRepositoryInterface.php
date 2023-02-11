<?php

namespace App\Repositories\Manga;

use App\Helpers\Repository\BaseRepositoryInterface;
use App\Models\NewFeed;
use Illuminate\Database\Eloquent\Collection;

interface NewFeedRepositoryInterface extends BaseRepositoryInterface
{
    public function findUserNewfeeds(int $userId, array $orderBy): Collection;

    public function getByNewfeedId(int $userId, int $newfeedId): ?NewFeed;
}
