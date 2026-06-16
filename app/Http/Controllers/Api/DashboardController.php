<?php


namespace App\Http\Controllers\Api;

use App\Actions\Dashboard\GetDashboardDataAction;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetDashboardDataAction $action): JsonResponse
    {
        return $this->successResponse(
            data: $action->execute($request->user()),
            message: 'Dashboard data retrieved successfully'
        );
    }
}