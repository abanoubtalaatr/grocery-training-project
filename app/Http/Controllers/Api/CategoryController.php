<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryMealsCollection;
use App\Actions\Category\GetAllCategoriesAction;
use App\Actions\Category\GetSingleCategoryAction;
use App\Services\Category\GetCategoryMealsService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * Get all categories
     */
    public function index(GetAllCategoriesAction $action): JsonResponse
    {
        return $this->successResponse(
            CategoryResource::collection($action->execute()), 
            'Categories retrieved successfully'
        );
    }

    /**
     * Get single category with meals
     */
    public function show(string $id, GetSingleCategoryAction $action): JsonResponse
    {
        return $this->successResponse(
            new CategoryResource($action->execute($id)), 
            'Category retrieved successfully'
        );
    }

    /**
     * Get meals by category (paginated)
     */
    public function meals(string $id, Request $request, GetCategoryMealsService $service): JsonResponse
    {
        $category = Category::findOrFail($id);
        $paginator = $service->execute($category, $request);

        $message = $paginator->total() === 0 
            ? 'No products match your filters.' 
            : 'Meals retrieved successfully';

        return $this->successResponse(
            new CategoryMealsCollection($paginator, $category), 
            $message
        );
    }
}