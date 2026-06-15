<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\MealService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodayDealController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $mealService;

    public function __construct(MealService $mealService)
    {
        $this->mealService = $mealService;
    }

    /**
     * Get today's deals (meals with active discounts).
     */
    public function index(Request $request): JsonResponse
    {
        $meals = Meal::with('category')
            ->available()
            ->withActiveDiscount()
            ->orderBy('created_at', 'desc')
            ->get();

        $formatted = $meals->map(fn($meal) => $this->mealService->formatMeal($meal));

        return self::collectionResponse('Today\'s deals retrieved successfully', $formatted);
    }
}
