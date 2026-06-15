<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class MealBrandController extends Controller
{
    use ApiResponse;

    /**
     * Get unique meal brands.
     */
    public function index(): JsonResponse
    {
        $brands = Meal::distinct()->pluck('brand')->filter();

        return self::successResponse('Brands retrieved successfully', $brands->values());
    }
}
