<?php

namespace App\Http\Controllers\Api\Meals;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealResource;
use App\Http\Controllers\Api\Actions\Meal\MoreToExploreAction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MoreToExploreController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $meals = (new MoreToExploreAction())->handle();
            return $this->successResponse('More to explore retrieved successfully', MealResource::collection($meals));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve more to explore', $e->getMessage(), 500);
        }
    }
}
