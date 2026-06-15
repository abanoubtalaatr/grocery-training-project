<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FrequencyService;
use App\Services\MealService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FrequencyMealController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $mealService;

    public function __construct(MealService $mealService)
    {
        $this->mealService = $mealService;
    }

    /**
     * Get meals the authenticated user orders most often.
     */
    public function index(Request $request): JsonResponse
    {
        $frequencyType = $request->input('frequency_type', FrequencyService::FREQUENCY_WEEKLY);
        if (!in_array($frequencyType, FrequencyService::VALID_TYPES, true)) {
            $frequencyType = FrequencyService::FREQUENCY_WEEKLY;
        }

        $user = $request->user();
        $subcategoryId = $request->input('subcategory_id');
        $subcategoryId = is_numeric($subcategoryId) ? (int) $subcategoryId : null;

        $service = app(FrequencyService::class);
        $meals = $service->getFrequentlyOrderedMeals($user, $frequencyType, 50, $subcategoryId);

        $formatted = $meals->map(function ($meal) {
            $data = $this->mealService->formatMeal($meal);
            $data['order_count'] = (int) $meal->getAttribute('order_count');
            return $data;
        });

        return self::collectionResponse('Frequency meals retrieved successfully', $formatted, [
            'frequency_type' => $frequencyType,
            'subcategory_id' => $subcategoryId,
        ]);
    }
}
