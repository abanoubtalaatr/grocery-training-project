<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MealService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendedMealController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $mealService;

    public function __construct(MealService $mealService)
    {
        $this->mealService = $mealService;
    }

    /**
     * Get recommended meals.
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);
        $recommendations = $this->mealService->getRecommendedMeals($limit);

        $formatted = $recommendations->map(function ($meal) {
            $data = $this->mealService->formatMeal($meal);
            $data['recommendation_reason'] = $this->getRecommendationReason($meal);
            return $data;
        });

        return self::collectionResponse('Meal recommendations retrieved successfully', $formatted);
    }

    private function getRecommendationReason($meal): string
    {
        if ($meal->is_featured && $meal->discount_price) {
            return 'Featured with special offer';
        }
        if ($meal->is_featured) {
            return 'Featured meal';
        }
        if ($meal->discount_price) {
            return 'Special offer';
        }
        return 'Popular choice';
    }
}
