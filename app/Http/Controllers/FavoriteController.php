<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repositories\Manga\FavoriteRepositoryInterface;
use App\Repositories\Manga\MangaRepositoryInterface;
use App\Requests\Manga\FavoriteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $favoriteRepository;

    protected $mangaRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository, MangaRepositoryInterface $mangaRepository)
    {
        parent::__construct();
        $this->favoriteRepository = $favoriteRepository;
        $this->mangaRepository = $mangaRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $orderListFiled = ['id', 'created_at', 'updated_at'];
        $orderBy = Helper::orderBy($request->get('sort_by'), $request->get('sort_direction'), $orderListFiled);

        $favoriteList = $this->favoriteRepository->findUserFavorites($this->currentUser->id, $orderBy);

        return $this->success(__('general.success'), $favoriteList);
    }


    public function store(FavoriteRequest $request, int $mangaId): JsonResponse
    {
        $isFavorite = $request->boolean('is_favorite', false);

        $manga = $this->mangaRepository->getById($mangaId);
        if (empty($manga)) {
            return $this->error(__('general.not_found'), [], 404);
        }

        $favorite = $this->favoriteRepository->getByFavoriteId($this->currentUser->id, $mangaId);
        if (empty($favorite) && empty($isFavorite)) {
            return $this->error(__('general.not_found'), [], 404);
        }

        if (empty($isFavorite) && !empty($favorite)) {
            $favoriteDel = $this->favoriteRepository->deleteById($favorite->id);
            if (empty($favoriteDel)) {
                return $this->error(__('general.server_error'), [], 500);
            }

            return $this->success(__('general.success'), $favoriteDel);
        }

        if (!empty($isFavorite) && empty($favorite)) {
            $favorite = $this->favoriteRepository->create([
                'user_id' => $this->currentUser->id,
                'manga_id' => $mangaId
            ]);

            if (empty($favorite)) {
                return $this->error(__('general.server_error'), [], 500);
            }
        }

        return $this->success(__('general.success'), $favorite);
    }
}
