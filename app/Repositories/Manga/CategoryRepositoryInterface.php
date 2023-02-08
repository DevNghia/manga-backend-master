<?php

namespace App\Repositories\Manga;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getCategories(?string $searchText, array $orderBy): LengthAwarePaginator;

    public function getById(int $id):? Category;
}
