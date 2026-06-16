<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\MealResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;
    /**
     * Get all categories
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $categories = Category::active()->ordered()->withCount('meals')->get();

            return $this->successResponse(
                'Categories retrieved successfully',
                CategoryResource::collection($categories),
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve categories', $e->getMessage(), 500);
        }
    }

    /**
     * Get single category with meals
     */
    public function show(string $id): JsonResponse
    {
        try {
            $category = Category::with(['meals' => function ($query) {
                $query->available()->orderBy('created_at', 'desc');
            }])->findOrFail($id);

            return $this->successResponse(
                'Category retrieved successfully',
                new CategoryResource($category->loadMissing('meals')),
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Category not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve category', $e->getMessage(), 500);
        }
    }
}
