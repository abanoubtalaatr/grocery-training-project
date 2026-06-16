<?php

namespace App\Http\Controllers\Api\Meals;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealResource;
use App\Http\Controllers\Api\Actions\Meal\HotAction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $meals = (new HotAction())->handle();
            return $this->successResponse('Hot meals retrieved successfully', MealResource::collection($meals));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve hot meals', $e->getMessage(), 500);
        }
    }
}
