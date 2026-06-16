<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    /**
     * Get dashboard statistics and insights.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(
            'Dashboard data retrieved successfully',
            $this->dashboardService->getDashboardData(
                $request->user()
            )
        );
    }
}