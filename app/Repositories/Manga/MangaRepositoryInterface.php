<?php

namespace App\Repositories\Manga;

use App\Models\Chapter;
use App\Models\Manga;
use App\Models\MangaView;
use Illuminate\Pagination\LengthAwarePaginator;

interface MangaRepositoryInterface
{
    /**
     * @param array $conditions
     * @param array $orderBy
     * @return LengthAwarePaginator
     */
    public function getMangaList(array $conditions, array $orderBy): LengthAwarePaginator;

    /**
     * @param int $mangaId
     * @return Manga|null
     */
    public function getById(int $mangaId):? Manga;

    public function getAndUpdateViewerManga(int $mangaId):? MangaView;
}
