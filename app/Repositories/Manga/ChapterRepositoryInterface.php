<?php

namespace App\Repositories\Manga;

use App\Models\Chapter;
use App\Models\ChapterViewer;
use Illuminate\Pagination\LengthAwarePaginator;

interface ChapterRepositoryInterface
{
    /**
     * @param int $mangaId
     * @param string $searchText
     * @param array $orderBy
     * @return LengthAwarePaginator
     */
    public function getMangaChapters(int $mangaId, string $searchText, array $orderBy): LengthAwarePaginator;

    /**
     * @param int $chapterId
     * @return Chapter|null
     */
    public function getById(int $chapterId):? Chapter;

    /**
     * @param int $mangaId
     * @param int $chapterId
     * @return ChapterViewer|null
     */
    public function getAndUpdateViewerChapter(int $mangaId, int $chapterId):? ChapterViewer;
}
