<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repositories\Manga\MangaRepositoryInterface;
use App\Repositories\Manga\NewFeedRepositoryInterface;
use App\Repositories\Manga\ChapterRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    protected $characterRepository;
    protected $newfeedRepository;
    protected $mangaRepository;

    public function __construct(ChapterRepositoryInterface $characterRepository, NewFeedRepositoryInterface  $newfeedRepository, MangaRepositoryInterface $mangaRepository)
    {
        parent::__construct();
        $this->characterRepository = $characterRepository;
        $this->newfeedRepository = $newfeedRepository;
        $this->mangaRepository = $mangaRepository;
    }

    public function index(int $mangaId, Request $request): JsonResponse
    {
        $searchText = $request->get('search_text');
        $orderListFiled = ['id', 'name', 'created_at', 'updated_at'];
        $orderBy = Helper::orderBy($request->get('sort_by'), $request->get('sort_direction'), $orderListFiled);
        $characters = $this->characterRepository->getMangaChapters($mangaId, $searchText, $orderBy);

        return $this->successWithPaginate(__('general.success'), $characters, 200);
    }

    public function show(int $id): JsonResponse
    {
        $character = $this->characterRepository->getById($id);
        if (empty($character)) {
            return $this->error(__('general.not_found'), [], 404);
        }

        $viewer = $this->characterRepository->getAndUpdateViewerChapter($character->manga_id, $id);
        $character = $character->toArray();
        $countViewer = !empty($viewer->count_viewer) ? $viewer->count_viewer : 0;
        $character = array_merge($character, ['viewer' => $countViewer]);


        return $this->success(__('general.success'), $character);
    }
}
