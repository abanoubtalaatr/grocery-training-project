<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Actions\Meal\MealIndexAction;
use App\Http\Controllers\Api\Actions\Meal\MealShowAction;
use App\Http\Resources\Api\MealResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    use ApiResponse;

    /**
     * Get all meals
     */
    public function index(Request $request, MealIndexAction $action): JsonResponse
    {
        try {
            $user = $request->user();
            $meals = $action->handle($request, $user);

            return $this->successResponse('Meals retrieved successfully', MealResource::collection($meals));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve meals', $e->getMessage(), 500);
        }
    }

    /**
     * Get single meal
     */
    public function show(string $id): JsonResponse
    {
        try {
            $meal = (new MealShowAction())->handle($id);

            return $this->successResponse('Meal retrieved successfully', new MealResource($meal));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->errorResponse('Meal not found', null, 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve meal', $e->getMessage(), 500);
        }
    }
}
