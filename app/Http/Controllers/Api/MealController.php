<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Meal\GetFrequencyMealsAction;
use App\Http\Actions\Api\Meal\GetMealsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Meal\FrequencyMealRequest;
use App\Http\Requests\Meal\MealIndexRequest;
use App\Http\Resources\Api\FrequencyMealResource;
use App\Http\Resources\Api\MealResource;
use App\Models\Meal;
use App\Services\FrequencyService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class MealController extends Controller
{
    use ApiResponse;

    public function frequency(
        FrequencyMealRequest $request,
        GetFrequencyMealsAction $action
    ): JsonResponse {

        $meals = $action->execute(
            $request->user(),
            $request->validated(
                'frequency_type',
                FrequencyService::FREQUENCY_WEEKLY
            ),
            $request->validated(
                'subcategory_id'
            )
        );

        return $this->successResponse(
            'Frequency meals retrieved successfully',
            FrequencyMealResource::collection(
                $meals
            )
        );
    }

    public function index(
        MealIndexRequest $request,
        GetMealsAction $action
    ): JsonResponse {

        $favoriteMealIds =
            $request->user()
                ?->favorites()
                ->pluck('meal_id')
                ->toArray()
            ?? [];

        $meals = $action->execute(
            $request->validated(),
            $favoriteMealIds
        );

        return $this->successResponse(
            'Meals retrieved successfully',
            MealResource::collection(
                $meals
            )
        );
    }
}