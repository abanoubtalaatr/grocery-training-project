<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\V1\ApiResponse;
use App\Services\AuthService;

class NewPasswordController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected AuthService $authService
    ) {}

    public function store (Request $request): JsonResponse
    {

        try {
            // Verify OTP
            $isValid = $this->authService->verifyOtp(
                $request->input('identifier'),
                $request->input('otp')
            );

            if (! $isValid) {
                return $this->errorResponse('Invalid or expired OTP', '400');
            }

            // Update password
            $user = $request->user();

            $user->update([
                'password' => $request->input('password'),
            ]);


            return $this->successResponse('Password changed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to change password', 'Internal server error', 500);
        }
    }
}
