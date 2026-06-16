<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LoyaltyService;
use App\Traits\ApiResponse;
use Illuminate\Http\{JsonResponse, Request};

class LoyaltyController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly LoyaltyService $loyaltyService,
    ) {}

  
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            data: $this->loyaltyService->buildSummary($request->user()),
            message: 'Loyalty data retrieved successfully'
        );
    }
}