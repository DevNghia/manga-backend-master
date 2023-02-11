<?php

namespace App\Repositories\Manga;

use App\Helpers\Helper;
use App\Helpers\Repository\BaseRepository;
use App\Models\NewFeed;
use Illuminate\Database\Eloquent\Collection;

class NewFeedRepository extends BaseRepository implements NewFeedRepositoryInterface
{
    protected $model;

    public function __construct(NewFeed $model)
    {
        $this->model = $model;
    }

    public function findUserNewfeeds(int $userId, array $orderBy): Collection
    {
        $query = NewFeed::query()
            ->with(['manga:id,type,title,slug,status,image,description'])
            ->where('user_id', $userId);

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query = $query->orderBy($field, $direction);
            }
        }
        return $query->get();
    }

    public function getByNewfeedId(int $userId, int $newfeedId): ?NewFeed
    {
        return NewFeed::query()
            ->where('id', $newfeedId)
            ->where('user_id', $userId)
            ->first();
    }
}
