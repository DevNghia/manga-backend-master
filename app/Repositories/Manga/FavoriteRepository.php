<?php

namespace App\Repositories\Manga;

use App\Helpers\Helper;
use App\Helpers\Repository\BaseRepository;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;

class FavoriteRepository extends BaseRepository implements FavoriteRepositoryInterface
{
    protected $model;

    public function __construct(Favorite $model)
    {
        $this->model = $model;
    }

    public function findUserFavorites(int $userId, array $orderBy): Collection
    {
        $query = Favorite::query()
            ->with(['manga:id,type,title,slug,status,image,description'])
            ->where('user_id', $userId);

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query = $query->orderBy($field, $direction);
            }
        }
        return $query->get();
    }

    public function getByFavoriteId(int $userId, int $favoriteId):? Favorite
    {
        return Favorite::query()
            ->where('id', $favoriteId)
            ->where('user_id', $userId)
            ->first();
    }
}
