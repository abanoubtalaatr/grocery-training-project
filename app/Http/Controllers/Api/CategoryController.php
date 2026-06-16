<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get all categories
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getCategories();
        $formatted = $categories->map(fn($cat) => $this->categoryService->formatCategory($cat));

        return self::collectionResponse('Categories retrieved successfully', $formatted);
    }

    /**
     * Get single category
     */
    public function show(Category $category): JsonResponse
    {
        return self::successResponse(
            'Category retrieved successfully',
            $this->categoryService->formatCategory($category)
        );
    }
}
