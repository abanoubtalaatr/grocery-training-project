<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\V1\ApiResponse;
use App\Services\AuthService;
use App\Http\Requests\Api\V1\Auth\ForgetPasswordRequest;

class ForgetPasswordController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected AuthService $authService
    ) {}

    public function __invoke(ForgetPasswordRequest $request) {
        // send OTP to email 
        try {
            $this->authService->forgotPassword($request->input('identifier'));

            return $this->successResponse('OTP sent successfully. Please check your email or phone.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send OTP', 'Internal server error', 500);
        }
    }
}
