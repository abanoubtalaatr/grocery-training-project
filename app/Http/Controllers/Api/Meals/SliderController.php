<?php

namespace App\Http\Controllers\Api\Meals;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealResource;
use App\Http\Controllers\Api\Actions\Meal\SliderAction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $meals = (new SliderAction())->handle();
            return $this->successResponse('Slider meals retrieved successfully', MealResource::collection($meals));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve slider meals', $e->getMessage(), 500);
        }
    }
}
