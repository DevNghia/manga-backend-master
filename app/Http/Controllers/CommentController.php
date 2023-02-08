<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Manga\MangaRepositoryInterface;
use App\Requests\Comment\CommentRequest;
use App\Requests\Comment\UpdateCommentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $mangaRepository;

    protected $commentRepository;

    public function __construct(MangaRepositoryInterface $mangaRepository, CommentRepositoryInterface $commentRepository)
    {
        parent::__construct();
        $this->mangaRepository = $mangaRepository;
        $this->commentRepository = $commentRepository;
    }

    public function index(Request $request, $mangaId): JsonResponse
    {
        $data = $request->only(['search_text', 'sort_by', 'sort_direction']);

        $orderListFiled = ['id', 'content', 'created_at', 'updated_at'];
        $orderBy = Helper::orderBy($data['sort_by'] ?? null, $data['sort_direction'] ?? null, $orderListFiled);
        $commentList = $this->commentRepository->commentList($mangaId, $data['search_text'] ?? null, $orderBy);

        return $this->successWithPaginate(__('general.success'), $commentList);
    }

    public function store(CommentRequest $request, $mangaId): JsonResponse
    {
        $data = $request->only(['content', 'comment_id', 'is_reply']);
        $data['user_id'] = $this->currentUser->id;
        $manga = $this->mangaRepository->getById($mangaId);
        if (empty($manga)) {
            return $this->error(__('general.manga_not_found'), null, 404);
        }

        $data['manga_id'] = $mangaId;
        if (!empty($data['is_reply'])) {
            $comment = $this->commentRepository->getById($data['comment_id']);
            if (empty($comment)) {
                return $this->error(__('general.comment_not_found'), null, 404);
            }

            $data['parent_id'] = $data['comment_id'];
        }

        $comment = $this->commentRepository->create($data);
        if (empty($comment)) {
            return $this->error(__('general.server_error'), null, 500);
        }

        $comment = $comment->load('user:id,name,email,phone_number,avatar,provider,is_active,last_login');

        return $this->success(__('general.success'), $comment);
    }

    public function update(UpdateCommentRequest $request, $mangaId, $commentId): JsonResponse
    {
        $data = $request->only(['content']);
        $manga = $this->mangaRepository->getById($mangaId);
        if (empty($manga)) {
            return $this->error(__('general.manga_not_found'), null, 404);
        }

        $comment = $this->commentRepository->getById($commentId);
        if (empty($comment)) {
            return $this->error(__('general.comment_not_found'), null, 404);
        }

        $comment = $this->commentRepository->updateById($commentId, $data);
        if (empty($comment)) {
            return $this->error(__('general.server_error'), null, 500);
        }

        $comment = $comment->load('user:id,name,email,phone_number,avatar,provider,is_active,last_login');

        return $this->success(__('general.success'), $comment);
    }

    public function destroy($mangaId, $commentId): JsonResponse
    {
        $manga = $this->mangaRepository->getById($mangaId);
        if (empty($manga)) {
            return $this->error(__('general.manga_not_found'), null, 404);
        }

        $comment = $this->commentRepository->getById($commentId);
        if (empty($comment)) {
            return $this->error(__('general.comment_not_found'), null, 404);
        }

        $comment = $this->commentRepository->deleteById($commentId);
        if (empty($comment)) {
            return $this->error(__('general.server_error'), null, 500);
        }

        return $this->success(__('general.success'), true);
    }
}
