<?php

namespace App\Repositories\Manga;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\Helper;
use App\Helpers\Repository\BaseRepository;
use App\Models\NewFeed;

use Illuminate\Database\Eloquent\Collection;

class NewFeedRepository extends BaseRepository implements NewFeedRepositoryInterface
{

    protected $model;
    protected $currentUser;
    public function __construct(NewFeed $model)
    {
        $this->model = $model;

        $this->currentUser = Auth::user();
    }

    public function findUserNewfeeds(int $userId, array $orderBy): Collection
    {
        $query = NewFeed::query()
            ->with(['user:id,name,email,avatar,is_active'])
            ->with(['chapter:id,thumbnail_url,is_active'])
            ->with(['comment:id,user_id,manga_id,chapter_id,parent_id,content,is_active'])
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
            ->with(['chapter:id,thumbnail_url,is_active'])
            ->where('id', $newfeedId)
            ->where('user_id', $userId)
            ->first();
    }
    public function getAndUpdateNewfeedId(int $userId, int $newfeedId, int $mangaId, int $chapterId): ?NewFeed
    {
        $viewer = NewFeed::query()
            ->where('id', $newfeedId)
            ->where('manga_id', $mangaId)
            ->where('chapter_id', $chapterId)
            ->where('user_id', $userId)
            ->first();
        if (empty($viewer)) {
            return NewFeed::query()->create([
                'user_id' => $this->currentUser->id,
                'manga_id' => $mangaId,
                'chapter_id' => $chapterId,
            ]);
        }
    }
}
