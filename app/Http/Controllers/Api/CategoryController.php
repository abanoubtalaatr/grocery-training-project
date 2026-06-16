<?php

namespace App\Http\Controllers\Api;

use App\Actions\Category\GetCategoriesAction;
use App\Actions\Category\GetCategoryAction;
use App\Actions\Category\GetCategoryMealsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryMealsRequest;
use App\Http\Resources\CategoryDetailsResource;
use App\Http\Resources\CategoryMealsResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(GetCategoriesAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully',
            'data' => CategoryResource::collection($action->execute()),
        ]);
    }

    public function show(Category $category, GetCategoryAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Category retrieved successfully',
            'data' => CategoryDetailsResource::make($action->execute($category)),
        ]);
    }

    public function meals(
        CategoryMealsRequest $request,
        Category $category,
        GetCategoryMealsAction $action
    ): JsonResponse {
        $paginator = $action->execute($category, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Meals retrieved successfully',
            'data' => CategoryMealsResource::make([
                'category' => $category,
                'paginator' => $paginator,
            ]),
        ]);
    }
}
