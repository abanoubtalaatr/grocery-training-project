<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService) {}

    public function store(ResetPasswordRequest $request): JsonResponse
    {
        $this->authService->resetPassword(
            $request->input('identifier'),
            $request->input('otp'),
            $request->input('password')
        );

        return self::successResponse('Password reset successfully');
    }
}
