<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\MealService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;

class NewProductController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $mealService;

    public function __construct(MealService $mealService)
    {
        $this->mealService = $mealService;
    }

    public function index(): JsonResponse
    {
        $meals = Meal::with('category')->available()->latest()->take(20)->get();
        $formatted = $meals->map(fn($meal) => $this->mealService->formatMeal($meal));

        return self::collectionResponse('New products retrieved successfully', $formatted);
    }
}
