<?php

namespace App\Http\Controllers;

use App\Helpers\Constant;
use App\Helpers\MangaHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $pageSize = $request->get('page_size');
        if (empty($pageSize)) {
            $pageSize = Constant::DEFAULT_PER_PAGE_HOME;
        }

        $results = [
            'manga_new' => MangaHelper::mangaNewCollections($pageSize),
            'hot_manga' => MangaHelper::mangaHotCollections($pageSize),
            'manga_last_update' => MangaHelper::mangaLastUpdateCollections($pageSize),
            'hot_update' => MangaHelper::hotUpdateChapter($pageSize),
        ];

        return $this->success(__('general.success'), $results);
    }
}
