<?php

namespace App\Http\Controllers\Api\Meals;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealResource;
use App\Http\Controllers\Api\Actions\Meal\TodayAction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodayController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $meals = (new TodayAction())->handle();
            return $this->successResponse('New products retrieved successfully', MealResource::collection($meals));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve new products', $e->getMessage(), 500);
        }
    }
}
