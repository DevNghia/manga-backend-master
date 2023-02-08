<?php

namespace App\Repositories\Manga;

use App\Helpers\Repository\BaseRepository;
use App\Models\Download;
use Illuminate\Database\Eloquent\Collection;

class DownloadRepository extends BaseRepository implements DownloadRepositoryInterface
{
    protected $model;

    public function __construct(Download $model)
    {
        $this->model = $model;
    }

    public function getDownloadList(int $userId, array $orderBy): Collection
    {
        $query = $this->model
            ->with(['manga:id,title,description,status,is_active,chapter_count,rank,release_at',
                'chapter', 'chapter.thumbnails'])
            ->where('user_id', $userId);

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query = $query->orderBy($field, $direction);
            }
        }

        return $query->get();
    }

    public function getChapterWithManga(int $userId, int $mangaId, int $chapterId): ?Download
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('manga_id', $mangaId)
            ->where('chapter_id', $chapterId)
            ->first();
    }
}
