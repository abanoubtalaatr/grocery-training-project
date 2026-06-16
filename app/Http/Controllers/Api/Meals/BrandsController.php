<?php

namespace App\Http\Controllers\Api\Meals;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Actions\Meal\BrandsAction;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $brands = (new BrandsAction())->handle();
            return $this->successResponse('Brands retrieved successfully', $brands);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve brands', $e->getMessage(), 500);
        }
    }
}
