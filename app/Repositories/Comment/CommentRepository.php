<?php

namespace App\Repositories\Comment;

use App\Helpers\Helper;
use App\Helpers\Repository\BaseRepository;
use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    protected $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function commentList(int $mangaId, ?string $searchText, array $orderBy): LengthAwarePaginator
    {
        $query = Comment::query()
            ->with(['user:id,name,email,phone_number,avatar,provider,is_active,last_login'])
            ->where('manga_id', $mangaId)
            ->where('is_active', true);

        if (!empty($searchText)) {
            $query = $query->where('content', 'like', '%'. $searchText . '%');
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query = $query->orderBy($field, $direction);
            }
        }

        return $query->paginate(Helper::getPerPage(), ["*"], Helper::getPageName(), Helper::getCurrentPage());
    }
}
