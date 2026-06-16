<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LoyaltyService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly LoyaltyService $loyaltyService,
    ) {}

    /**
     * Loyalty & rewards summary for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->loyaltyService->buildSummary($request->user());
            return self::successResponse('Loyalty data retrieved successfully', $data);
        } catch (\Exception $e) {
            return self::errorResponse(
                'Failed to retrieve loyalty data',
                $e->getMessage(),
                500
            );
        }
    }
}
