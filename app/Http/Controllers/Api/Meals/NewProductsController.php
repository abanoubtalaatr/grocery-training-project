<?php

namespace App\Http\Controllers\Api\Meals;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MealResource;
use App\Http\Controllers\Api\Actions\Meal\NewProductsAction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewProductsController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $meals = (new NewProductsAction())->handle();
            return $this->successResponse('New products retrieved successfully', MealResource::collection($meals));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve meals', $e->getMessage(), 500);
        }
    }
}
