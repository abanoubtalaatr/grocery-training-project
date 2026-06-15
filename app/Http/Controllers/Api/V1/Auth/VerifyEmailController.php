<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\V1\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected AuthService $authService
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->authService->verifyOtp($request->input('identifier'), $request->input('otp'));

            return $this->successResponse('Email verified successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to verify email', 'Internal server error', 500);
        }
    }
}
