<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\GetCategoryMealsRequest;
use App\Actions\Category\GetCategoriesAction;
use App\Actions\Category\GetCategoryAction;
use App\Actions\Category\GetCategoryMealsAction;
use App\Actions\Category\SearchCategoriesAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(GetCategoriesAction $action): JsonResponse
    {
        try {
            $categories = $action();

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id, GetCategoryAction $action): JsonResponse
    {
        try {
            $data = $action($id);

            return response()->json([
                'success' => true,
                'message' => 'Category retrieved successfully',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function meals(string $id, GetCategoryMealsRequest $request, GetCategoryMealsAction $action): JsonResponse
    {
        try {
            $result = $action($id, $request);

            return response()->json(array_merge([
                'success' => true,
                'message' => $result['total'] === 0 ? 'No products match your filters.' : 'Meals retrieved successfully',
                'data' => $result['data'],
            ], $result['total'] === 0 ? ['empty_message' => 'No products match the applied filters. Try adjusting your filters.'] : []));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve meals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function Search(Request $request, SearchCategoriesAction $action): JsonResponse
    {
        try {
            $categories = $action($request);
                
            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
