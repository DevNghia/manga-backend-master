<?php

namespace App\Repositories\Manga;

use App\Helpers\Repository\BaseRepositoryInterface;
use App\Models\NewFeed;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface NewFeedRepositoryInterface extends BaseRepositoryInterface
{
    public function findUserNewfeeds(int $userId, array $orderBy): LengthAwarePaginator;
    public function getByNewfeedId(int $userId, int $newfeedId): ?NewFeed;
    public function getAndUpdateNewfeedId(int $userId, int $newfeedId, int $mangaId, int $chapterId): ?NewFeed;
}
