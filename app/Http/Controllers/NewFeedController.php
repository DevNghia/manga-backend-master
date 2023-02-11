<?php

namespace App\Http\Controllers;

use App\Repositories\Manga\MangaRepositoryInterface;
use App\Repositories\Manga\NewFeedRepositoryInterface;
use App\Requests\Manga\NewfeedRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class NewFeedController extends Controller
{
    protected $newfeedRepository;

    protected $mangaRepository;

    public function __construct(NewFeedRepositoryInterface  $newfeedRepository, MangaRepositoryInterface $mangaRepository)
    {
        parent::__construct();
        $this->newfeedRepository = $newfeedRepository;
        $this->mangaRepository = $mangaRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $orderListFiled = ['id', 'created_at', 'updated_at'];
        $orderBy = Helper::orderBy($request->get('sort_by'), $request->get('sort_direction'), $orderListFiled);

        $newfeedList = $this->newfeedRepository->findUserNewfeeds($this->currentUser->id, $orderBy);

        return $this->success(__('general.success'), $newfeedList);
    }


    public function store(NewfeedRequest $request, int $mangaId): JsonResponse
    {
        $isNewfeed = $request->boolean('is_newfeed', false);

        $manga = $this->mangaRepository->getById($mangaId);
        if (empty($manga)) {
            return $this->error(__('general.not_found'), [], 404);
        }

        $newfeed = $this->newfeedRepository->getByNewfeedId($this->currentUser->id, $mangaId);
        if (empty($newfeed) && empty($isNewfeed)) {
            return $this->error(__('general.not_found'), [], 404);
        }

        if (empty($isNewfeed) && !empty($newfeed)) {
            $newfeedDel = $this->newfeedRepository->deleteById($newfeed->id);
            if (empty($newfeedDel)) {
                return $this->error(__('general.server_error'), [], 500);
            }

            return $this->success(__('general.success'), $newfeedDel);
        }

        if (!empty($isNewfeed) && empty($newfeed)) {
            $newfeed = $this->newfeedRepository->create([
                'user_id' => $this->currentUser->id,
                'manga_id' => $mangaId
            ]);

            if (empty($newfeed)) {
                return $this->error(__('general.server_error'), [], 500);
            }
        }

        return $this->success(__('general.success'), $newfeed);
    }
}
