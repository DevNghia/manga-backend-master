<?php

namespace App\Repositories\Manga;

use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function getCategories(?string $searchText, array $orderBy): LengthAwarePaginator
    {
        $query = Category::query()
            ->where('is_active', true);
        if (!empty($searchText)) {
            $query = $query->where('title', 'like', '%'. $searchText . '%')
                ->orWhere('description', 'like', '%'. $searchText . '%');
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $query = $query->orderBy($field, $direction);
            }
        }

        return $query->paginate(Helper::getPerPage(), ["*"], Helper::getPageName(), Helper::getCurrentPage());
    }

    public function getById(int $id): ?Category
    {
        return Category::query()
            ->where('id', $id)
            ->first();
    }
}
