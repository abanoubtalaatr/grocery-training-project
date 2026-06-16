<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subcategory\GetSubcategoriesRequest;
use App\Http\Requests\Subcategory\GetSubcategoryMealsRequest;
use App\Actions\Subcategory\GetSubcategoriesAction;
use App\Actions\Subcategory\GetSubcategoryAction;
use App\Actions\Subcategory\GetSubcategoryMealsAction;
use Illuminate\Http\JsonResponse;

class SubcategoryController extends Controller
{
    public function index(GetSubcategoriesRequest $request, GetSubcategoriesAction $action): JsonResponse
    {
        try {
            $subcategories = $action($request->input('category_id'));

            return response()->json([
                'success' => true,
                'message' => 'Subcategories retrieved successfully',
                'data' => $subcategories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subcategories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id, GetSubcategoryAction $action): JsonResponse
    {
        try {
            $data = $action($id);

            return response()->json([
                'success' => true,
                'message' => 'Subcategory retrieved successfully',
                'data' => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subcategory not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subcategory',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function meals(string $id, GetSubcategoryMealsRequest $request, GetSubcategoryMealsAction $action): JsonResponse
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
                'message' => 'Subcategory not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve meals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
