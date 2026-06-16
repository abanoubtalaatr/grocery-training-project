<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Category\GetCategoryMealsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\CategoryMealsRequest;
use App\Http\Resources\Api\Category\CategoryResource;
use App\Http\Resources\Category\CategoryDetailsResource;
use App\Http\Resources\Category\MealCardResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $categories = Category::active()
            ->ordered()
            ->withCount('meals')
            ->get();

        return $this->successResponse(
            'Categories retrieved successfully',
            CategoryResource::collection(
                $categories
            )
        );
    }

    public function show(string $id): JsonResponse
    {
        $category = Category::with([
            'meals' => fn ($query) =>
                $query->available()
                    ->latest()
        ])->findOrFail($id);

        return $this->successResponse(
            'Category retrieved successfully',
            new CategoryDetailsResource(
                $category
            )
        );
    }

    public function meals(
        string $id,
        CategoryMealsRequest $request,
        GetCategoryMealsAction $action
    ): JsonResponse {

        $category = Category::findOrFail($id);

        $meals = $action->execute(
            $category,
            $request->validated()
        );

        return $this->successResponse(
            'Meals retrieved successfully',
            [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],

                'meals' => MealCardResource::collection(
                    $meals->items()
                ),

                'pagination' => [
                    'current_page' => $meals->currentPage(),
                    'last_page' => $meals->lastPage(),
                    'per_page' => $meals->perPage(),
                    'total' => $meals->total(),
                ],
            ]
        );
    }
}
