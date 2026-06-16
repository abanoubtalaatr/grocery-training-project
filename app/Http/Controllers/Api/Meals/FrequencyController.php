<?php

namespace App\Http\Controllers\Api\Meals;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealResource;
use App\Http\Controllers\Api\Actions\Meal\FrequencyAction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FrequencyController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $frequencyType = $request->input('frequency_type');
            $subcategoryId = $request->input('subcategory_id');
            $limit = (int) $request->input('limit', 50);

            $meals = (new FrequencyAction())->handle($user, $frequencyType, $limit, $subcategoryId);

            return $this->successResponse('Frequency meals retrieved successfully', MealResource::collection($meals));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to load frequency meals', $e->getMessage(), 500);
        }
    }
}
