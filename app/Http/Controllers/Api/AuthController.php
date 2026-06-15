<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Resources\AuthUserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => (new AuthUserResource($result['user']))->withToken($result['token']),
        ], 201);
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login(
                $request->validated('login'),
                $request->validated('password')
            );
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => $e->errors(),
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => (new AuthUserResource($result['user']))->withToken($result['token']),
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Forgot password - send OTP
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgotPassword($request->validated('identifier'));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully. Please check your email or phone.',
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $isValid = $this->authService->verifyOtp(
            $request->validated('identifier'),
            $request->validated('otp')
        );

        if (!$isValid) {
            throw new BusinessException('Invalid or expired OTP', 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->resetPassword(
                $request->validated('identifier'),
                $request->validated('otp'),
                $request->validated('password')
            );
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed',
                'errors' => $e->errors(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => (new AuthUserResource($request->user()))->includeVerification(),
        ]);
    }

    /**
     * Delete account
     */
    public function deleteAccount(DeleteAccountRequest $request): JsonResponse
    {
        $this->authService->deleteAccount($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
        ]);
    }

    /**
     * Change password for authenticated user
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update([
            'password' => $request->validated('password'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }
}
