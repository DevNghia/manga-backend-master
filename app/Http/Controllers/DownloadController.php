<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repositories\Manga\DownloadRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    protected $downloadRepository;

    public function __construct(DownloadRepositoryInterface $downloadRepository)
    {
        parent::__construct();
        $this->downloadRepository = $downloadRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $orderListFiled = ['id', 'created_at', 'updated_at'];
        $orderBy = Helper::orderBy($request->get('sort_by'), $request->get('sort_direction'), $orderListFiled);
        $downloadList = $this->downloadRepository->getDownloadList($this->currentUser->id, $orderBy);

        return $this->success(__('general.success'), $downloadList);
    }

    public function download($mangaId, $chapterId): JsonResponse
    {
        $download = $this->downloadRepository->getChapterWithManga($this->currentUser->id, $mangaId, $chapterId);
        if (!empty($download)) {
            $counter = !empty($download->counter) ? $download->counter  + 1 : $download->counter;
            $download = $this->downloadRepository->updateById($download->id, [
                'counter' => $counter,
            ]);

            if (empty($download)) {
                return $this->error(__('general.server_error'));
            }
        }

        if (empty($download)) {
            $download = $this->downloadRepository->create([
                'manga_id' => $mangaId,
                'chapter_id' => $chapterId,
                'user_id' => $this->currentUser->id,
                'counter' => 1
            ]);

            if (empty($download)) {
                return $this->error(__('general.server_error'));
            }

        }

        return $this->success(__('general.success'), $download);
    }
}
