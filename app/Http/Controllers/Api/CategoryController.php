<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryDetailsResource;
use App\Http\Resources\MealResource;

class CategoryController extends Controller
{
    /**
     * Get all categories 
     * 
     */
   public function index(): JsonResponse
{
    $categories = CategoryResource::collection(
        Category::active()
            ->ordered()
            ->withCount('meals')
            ->get()
    );

    return response()->json([
        'success' => true,
        'message' => 'Categories retrieved successfully',
        'data' => $categories,
    ]);
}
    /**
     * Get single category with meals
     */
   public function show(string $id): JsonResponse
{
    $category = Category::with([
        'meals' => fn ($q) => $q
            ->available()
            ->latest()
    ])->findOrFail($id);

    return response()->json([
        'success' => true,
        'message' => 'Category retrieved successfully',
        'data' => new CategoryDetailsResource($category),
    ]);
}

    /**
     * Get meals by category (paginated)
     */
 public function meals(
    string $id,
    Request $request,
    GetCategoryMealsAction $action
): JsonResponse
{
    $category = Category::findOrFail($id);

    $paginator = $action->execute(
        $category,
        $request
    );

    return response()->json([
        'success' => true,
        'message' => 'Meals retrieved successfully',
        'data' => [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],

            'meals' => MealResource::collection(
                $paginator->getCollection()
            ),

            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ],
    ]);
}}