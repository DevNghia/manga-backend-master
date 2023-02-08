<?php

namespace App\Repositories\Manga;

use App\Helpers\Helper;
use App\Models\Chapter;
use App\Models\ChapterViewer;
use Illuminate\Pagination\LengthAwarePaginator;

class ChapterRepository implements ChapterRepositoryInterface
{
    public function getMangaChapters(int $mangaId, ?string $searchText, array $orderBy): LengthAwarePaginator
    {
        $query = Chapter::query()
            ->where('manga_id', $mangaId)
            ->where('is_active', true);
        if (!empty($searchText)) {
            $query = $query->where('name', 'like', '%'. $searchText . '%');
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query = $query->orderBy($field, $direction);
            }
        }

        return $query->paginate(Helper::getPerPage(), ["*"], Helper::getPageName(), Helper::getCurrentPage());
    }

    public function getById(int $chapterId): ?Chapter
    {
        return Chapter::query()
            ->with(['thumbnails:id,chapter_id,thumbnail_url,is_active'])
            ->whereHas('thumbnails', function ($q) {
                $q->where('is_active', true);
            })
            ->where('id', $chapterId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * @param int $mangaId
     * @param int $chapterId
     * @return ChapterViewer|null
     */
    public function getAndUpdateViewerChapter(int $mangaId, int $chapterId): ?ChapterViewer
    {
        $viewer = ChapterViewer::query()
            ->where('manga_id', $mangaId)
            ->where('chapter_id', $chapterId)
            ->first();
        if (empty($viewer)) {
            return ChapterViewer::query()->create([
                'manga_id' => $mangaId,
                'chapter_id' => $chapterId,
                'count_viewer' => 1
            ]);
        }

        $countViewer = !empty($viewer->count_viewer) ? $viewer->count_viewer + 1 : $viewer->count_viewer;
        return tap($viewer)->update([
            'count_viewer' => $countViewer
        ]);
    }
}
