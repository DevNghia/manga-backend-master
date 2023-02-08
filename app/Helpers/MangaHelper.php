<?php

namespace App\Helpers;

use App\Models\ChapterViewer;
use App\Models\Manga;
use App\Models\MangaView;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MangaHelper
{
    public static function mangaLastUpdateCollections(int $pageSize= Constant::DEFAULT_PER_PAGE_HOME): Collection
    {
        return self::queryManga()
            ->orderBy('id', 'DESC')
            ->take($pageSize)->get();
    }

    public static function mangaNewCollections(int $pageSize= Constant::DEFAULT_PER_PAGE_HOME): Collection
    {
        return self::queryManga()
            ->orderBy('id', 'DESC')
            ->take($pageSize)->get();
    }

    public static function mangaHotCollections(int $pageSize= Constant::DEFAULT_PER_PAGE_HOME): Collection
    {
        $mangaViewers = MangaView::query()
            ->orderBy('count_viewer', 'DESC')
            ->take($pageSize)->get();
        if ($mangaViewers->isEmpty()) {
            return self::mangaNewCollections($pageSize);
        }

        if ($mangaViewers->count() < $pageSize) {
            $collectionAppends = self::appendManga($mangaViewers->pluck('manga_id')->toArray(), $pageSize - $mangaViewers->count());
            return $mangaViewers->merge($collectionAppends);
        }

        return $mangaViewers;
    }

    public static function hotUpdateChapter(int $pageSize= Constant::DEFAULT_PER_PAGE_HOME): Collection
    {
        $hotChapterMangaIds = ChapterViewer::query()
            ->select(DB::raw('manga_id, max(`count_viewer`) as `countViewer`'))
            ->groupBy('manga_id')
            ->orderBy('countViewer', 'desc')
            ->take($pageSize)
            ->pluck('manga_id')
            ->toArray();
        if (empty($hotChapterMangaIds)) {
            return self::mangaNewCollections($pageSize);
        }

        $mangaList = self::queryManga()->whereIn('id', $hotChapterMangaIds)
            ->get();
        if ($mangaList->isEmpty()) {
            return self::mangaNewCollections($pageSize);
        }

        if (count($hotChapterMangaIds) < $pageSize) {
            $collectionAppends = self::appendManga($hotChapterMangaIds, $pageSize - count($hotChapterMangaIds));
            return $mangaList->merge($collectionAppends);
        }

        return $mangaList;
    }

    private static function queryManga(): \Illuminate\Database\Eloquent\Builder
    {
        return Manga::query()
            ->with(['authors:id,name', 'categories:id,title,description'])
            ->where('is_active', true);
    }

    private static function appendManga(array $mangaIds, int $totalLimit = Constant::DEFAULT_PER_PAGE_HOME): Collection
    {
        return self::queryManga()
            ->whereNotIn('id', $mangaIds)
            ->take($totalLimit)
            ->get();
    }
}
