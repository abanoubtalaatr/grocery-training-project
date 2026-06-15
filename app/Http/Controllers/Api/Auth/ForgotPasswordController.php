<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Services\AuthService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService) {}

    public function store(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgotPassword($request->input('identifier'));
        return self::successResponse('OTP sent successfully. Please check your email or phone.');
    }
}
