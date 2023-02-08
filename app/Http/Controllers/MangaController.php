<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\MangaCategory;
use App\Repositories\Manga\CategoryRepositoryInterface;
use App\Repositories\Manga\MangaRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MangaController extends Controller
{
    protected $categoryRepository;

    protected $mangaRepository;

    public function __construct(MangaRepositoryInterface $mangaRepository, CategoryRepositoryInterface $categoryRepository)
    {
        parent::__construct();
        $this->mangaRepository = $mangaRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->only(['category_id', 'search_text', 'type']);
       $data['manga_ids'] = [];

        $orderListFiled = ['id', 'title', 'description', 'created_at', 'updated_at'];
        $orderBy = Helper::orderBy($request->get('sort_by'), $request->get('sort_direction'), $orderListFiled);

        if (!empty($data['category_id'])) {
            $category = $this->categoryRepository->getById($data['category_id']);
            if (empty($category)) {
                return $this->error(__('general.not_found_category'), [], 404);
            }

            $categoryMangaIds = MangaCategory::query()
                ->where('category_id', $data['category_id'])
                ->pluck('manga_id')
                ->toArray();
            if (empty($categoryMangaIds)) {
                return $this->successWithPaginateNull(__('general.success'));
            }

            $data['manga_ids'] = $categoryMangaIds;
        }

        $mangaList = $this->mangaRepository->getMangaList($data, $orderBy);

        return $this->successWithPaginate(__('general.success'), $mangaList);
    }

    public function show(int $mangaId): JsonResponse
    {
        $manga = $this->mangaRepository->getById($mangaId);
        if (empty($manga)) {
            return $this->error(__('general.not_found'), [], 404);
        }

        $manga = $manga->toArray();
        $viewer = $this->mangaRepository->getAndUpdateViewerManga($mangaId);
        $countViewer = !empty($viewer->count_viewer) ? $viewer->count_viewer : 0;
        $manga = array_merge($manga, ['viewer' => $countViewer]);

        return $this->success(__('general.success'), $manga, 200);
    }
}
