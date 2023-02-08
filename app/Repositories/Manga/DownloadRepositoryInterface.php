<?php

namespace App\Repositories\Manga;

use App\Helpers\Repository\BaseRepositoryInterface;
use App\Models\Download;
use Illuminate\Database\Eloquent\Collection;

interface DownloadRepositoryInterface extends BaseRepositoryInterface
{
    public function getDownloadList(int $userId, array $orderBy): Collection;

    /**
     * @param int $userId
     * @param int $mangaId
     * @param int $chapterId
     * @return Download|null
     */
    public function getChapterWithManga(int $userId, int $mangaId, int $chapterId):? Download;
}
