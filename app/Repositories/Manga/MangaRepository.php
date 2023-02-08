<?php

namespace App\Repositories\Manga;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Manga;
use App\Models\MangaCategory;
use App\Models\MangaView;
use Illuminate\Pagination\LengthAwarePaginator;

class MangaRepository implements MangaRepositoryInterface
{
    protected $model;

    public function __construct(Manga $model)
    {
        $this->model = $model;
    }

    public function getMangaList(array $conditions, array $orderBy): LengthAwarePaginator
    {
        $query = Manga::query()
            ->with(['authors:id,name', 'categories:id,title,description'])
            ->where('is_active', true);
        $searchText = $conditions['search_text'] ?? null;
        if (!empty($searchText)) {
            $query = $query->where('title', 'like', '%'. $searchText . '%')
                ->orWhere('description', 'like', '%'. $searchText . '%');
        }

        if (!empty($conditions['manga_ids'])) {
            $query = $query->whereIn('id', $conditions['manga_ids']);
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query = $query->orderBy($field, $direction);
            }
        }

        return $query->paginate(Helper::getPerPage(), ["*"], Helper::getPageName(), Helper::getCurrentPage());
    }

    public function getById(int $mangaId): ?Manga
    {
        return $this->model
            ->with(['authors:id,name',
                'categories:id,title,is_active,description',
                'chapters:id,manga_id,name,is_active,thumbnail_count,created_at,updated_at'
            ])
//            ->whereHas('categories', function ($q) {
//                $q->where('is_active', true);
//            })
//            ->whereHas('characters', function ($q) {
//                $q->where('is_active', true);
//            })
            ->where('id', $mangaId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * @param int $mangaId
     * @return MangaView|null
     */
    public function getAndUpdateViewerManga(int $mangaId): ?MangaView
    {
        $viewer = MangaView::query()->where('manga_id', $mangaId)->first();
        if (empty($viewer)) {
            return MangaView::query()->create([
                'manga_id' => $mangaId,
                'count_viewer' => 1
            ]);
        }

        $countViewer = !empty($viewer->count_viewer) ? $viewer->count_viewer + 1 : $viewer->count_viewer;
        return tap($viewer)->update([
            'count_viewer' => $countViewer
        ]);
    }
}
