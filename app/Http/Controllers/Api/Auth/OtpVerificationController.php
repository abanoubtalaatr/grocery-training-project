<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\AuthService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class OtpVerificationController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService) {}

    public function store(VerifyOtpRequest $request): JsonResponse
    {
        $isValid = $this->authService->verifyOtp(
            $request->input('identifier'),
            $request->input('otp')
        );

        if (!$isValid) {
            return self::errorResponse('Invalid or expired OTP', null, 400);
        }

        return self::successResponse('OTP verified successfully');
    }
}
