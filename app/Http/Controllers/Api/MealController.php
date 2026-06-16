<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FrequencyService;
use App\Http\Requests\Meal\SearchMealsRequest;
use App\Actions\Meal\GetFrequencyMealsAction;
use App\Actions\Meal\GetMealsListAction;
use App\Actions\Meal\SearchMealsAction;
use App\Actions\Meal\GetRecommendedMealsAction;
use App\Actions\Meal\GetMealAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class MealController extends Controller
{
    public function frequency(Request $request, GetFrequencyMealsAction $action): JsonResponse
    {
        try {
            $frequencyType = $request->input('frequency_type', FrequencyService::FREQUENCY_WEEKLY);
            $subcategoryId = $request->input('subcategory_id');
            $subcategoryId = is_numeric($subcategoryId) ? (int) $subcategoryId : null;

            $data = $action($request->user(), $frequencyType, $subcategoryId);

            $payload = [
                'success' => true,
                'message' => 'Frequency meals retrieved successfully',
                'frequency_type' => $frequencyType,
                'data' => $data,
            ];
            
            if ($subcategoryId !== null) {
                $payload['subcategory_id'] = $subcategoryId;
            }

            return response()->json($payload);
        } catch (Throwable $e) {
            if ($e->getCode() === 401) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 401);
            }

            Log::error('Frequency meals error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load frequency meals',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    public function moreToExplore(GetMealsListAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'More to explore retrieved successfully',
            'data' => $action->moreToExplore(),
        ]);
    }

    public function brands(GetMealsListAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Brands retrieved successfully',
            'data' => $action->brands(),
        ]);
    }

    public function slider(GetMealsListAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Today\'s meals retrieved successfully',
            'data' => $action->slider(),
        ]);
    }

    public function bestSells(GetMealsListAction $action): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Best sells retrieved successfully',
            'data' => $action->bestSells(),
        ]);
    }

    public function newProducts(GetMealsListAction $action): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'New products retrieved successfully',
                'data' => $action->newProducts(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve meals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function hot(GetMealsListAction $action): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Hot meals retrieved successfully',
                'data' => $action->hot(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve hot meals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function today(GetMealsListAction $action): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Today\'s deals retrieved successfully',
                'data' => $action->today(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve today\'s deals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(SearchMealsRequest $request, SearchMealsAction $action): JsonResponse
    {
        try {
            $filters = $request->getFilters();
            $meals = $action($filters, $request->user());
            
            $totalCount = $meals->count();
            $isEmpty = $totalCount === 0;

            return response()->json(array_merge([
                'success' => true,
                'message' => $isEmpty ? 'No products match your filters.' : 'Meals retrieved successfully',
                'data' => $meals,
                'total_count' => $totalCount,
                'filters_applied' => array_merge($request->validated(), [
                    'sort_by' => $filters['sort_by'],
                    'sort_order' => $filters['sort_order'],
                ]),
            ], $isEmpty ? ['empty_message' => 'No products match the applied filters. Try adjusting your search or filters.'] : []));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve meals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function recommendations(Request $request, GetRecommendedMealsAction $action): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $meals = $action($limit);

            return response()->json([
                'success' => true,
                'message' => 'Meal recommendations retrieved successfully',
                'data' => $meals,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recommendations',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id, GetMealAction $action): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Meal retrieved successfully',
                'data' => $action($id),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Meal not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve meal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
