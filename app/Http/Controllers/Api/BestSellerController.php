<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\MealService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;

class BestSellerController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $mealService;

    public function __construct(MealService $mealService)
    {
        $this->mealService = $mealService;
    }

    public function index(): JsonResponse
    {
        $meals = Meal::with('category')->available()->orderBy('sold_count', 'desc')->take(10)->get();
        $formatted = $meals->map(fn($meal) => $this->mealService->formatMeal($meal));

        return self::collectionResponse('Best sellers retrieved successfully', $formatted);
    }
}
