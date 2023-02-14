<?php

namespace App\Http\Controllers;

use App\Repositories\Manga\MangaRepositoryInterface;
use App\Repositories\Manga\NewFeedRepositoryInterface;
use App\Repositories\Manga\ChapterRepositoryInterface;
use App\Requests\Manga\NewfeedRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class NewFeedController extends Controller
{
    protected $newfeedRepository;
    protected $characterRepository;
    protected $mangaRepository;

    public function __construct(ChapterRepositoryInterface $characterRepository, NewFeedRepositoryInterface  $newfeedRepository, MangaRepositoryInterface $mangaRepository)
    {
        parent::__construct();
        $this->newfeedRepository = $newfeedRepository;
        $this->mangaRepository = $mangaRepository;
        $this->characterRepository = $characterRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $newfeedListFiled = ['id', 'created_at', 'updated_at'];
        $newfeed = Helper::orderBy($request->get('sort_by'), $request->get('sort_direction'), $newfeedListFiled);

        $newfeedList = $this->newfeedRepository->findUserNewfeeds($this->currentUser->id, $newfeed);

        return $this->success(__('general.success'), $newfeedList);
    }


    public function show(int $mangaId, int $id): JsonResponse
    {
        $manga = $this->mangaRepository->getById($mangaId);
        if (empty($manga)) {
            return $this->error(__('general.not_found'), [], 404);
        }
        $character = $this->characterRepository->getById($id);

        $this->newfeedRepository->create([
            'user_id' => $this->currentUser->id,
            'manga_id' => $mangaId,
            'chapter_id' => $character->id,
            'comment_id' => 0,
        ]);
        // }
        if (empty($character)) {
            return $this->error(__('general.not_found'), [], 404);
        }

        $viewer = $this->characterRepository->getAndUpdateViewerChapter($character->manga_id, $id);
        $character = $character->toArray();
        $countViewer = !empty($viewer->count_viewer) ? $viewer->count_viewer : 0;
        $character = array_merge($character, ['viewer' => $countViewer]);


        return $this->success(__('general.success'), $character);
    }
    public function detail(int $id): JsonResponse
    {
        $newfeedDetail = $this->newfeedRepository->getByNewfeedId($this->currentUser->id, $id);

        return $this->success(__('general.success'), $newfeedDetail);
    }
}
