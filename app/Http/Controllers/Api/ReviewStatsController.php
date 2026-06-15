<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\ReviewService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class ReviewStatsController extends Controller
{
    use ApiResponse;

    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Get review statistics for a meal.
     */
    public function show(Meal $meal): JsonResponse
    {
        $stats = $this->reviewService->getMealStats($meal->id);
        
        return self::successResponse('Review stats retrieved successfully', $stats);
    }
}
