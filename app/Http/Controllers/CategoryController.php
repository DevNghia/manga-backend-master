<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repositories\Manga\CategoryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->only(['search_text', 'sort_by', 'sort_direction']);

        $orderListFiled = ['id', 'title', 'description', 'created_at', 'updated_at'];
        $orderBy = Helper::orderBy($data['sort_by'] ?? null, $data['sort_direction'] ?? null, $orderListFiled);

        $categories = $this->categoryRepository->getCategories($data['search_text'] ?? '', $orderBy);

        return $this->successWithPaginate(__('general.success'), $categories);
    }
}
