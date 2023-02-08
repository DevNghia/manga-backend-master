<?php

namespace App\Repositories\Manga;

use App\Helpers\Repository\BaseRepositoryInterface;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;

interface FavoriteRepositoryInterface extends BaseRepositoryInterface
{
    public function findUserFavorites(int $userId, array $orderBy): Collection;

    public function getByFavoriteId(int $userId, int $favoriteId):? Favorite;
}
