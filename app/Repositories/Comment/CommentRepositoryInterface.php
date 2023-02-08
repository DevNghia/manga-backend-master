<?php

namespace App\Repositories\Comment;

use App\Helpers\Repository\BaseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function commentList(int $mangaId, ?string $searchText, array $orderBy): LengthAwarePaginator;
}
