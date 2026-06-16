<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LoyaltyService;
use App\Traits\ResponseApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{ use ResponseApi;
    public function __construct(
        private readonly LoyaltyService $loyaltyService,
    ) {}

    /**
     * Loyalty & rewards summary for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return $this->success('Loyalty data retrieved successfully',$this->loyaltyService->buildSummary($request->user()));
        } catch (\Exception $e) {
            return $this->failed('Failed to retrieve loyalty data',$e->getMessage(), 500);
        }
    }
}
ll