<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Actions\Auth\RegisterAction;
use App\Http\Controllers\Api\Actions\Auth\LoginAction;
use App\Http\Controllers\Api\Actions\Auth\LogoutAction;
use App\Http\Controllers\Api\Actions\Auth\ForgotPasswordAction;
use App\Http\Controllers\Api\Actions\Auth\VerifyOtpAction;
use App\Http\Controllers\Api\Actions\Auth\ResetPasswordAction;
use App\Http\Controllers\Api\Actions\Auth\DeleteAccountAction;
use App\Http\Controllers\Api\Actions\Auth\ChangePasswordAction;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = (new RegisterAction($this->authService))->handle($request->validated());

            return $this->successResponse(
                'Registration successful',
                [
                    'user' => new UserResource($result['user']),
                    'token' => $result['token'],
                ],
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Registration failed', $e->getMessage(), 500);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = (new LoginAction($this->authService))->handle(
                $request->input('login'),
                $request->input('password')
            );

            return $this->successResponse(
                'Login successful',
                [
                    'user' => new UserResource($result['user']),
                    'token' => $result['token'],
                ]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Login failed', $e->errors(), 401);
        } catch (\Exception $e) {
            return $this->errorResponse('Login failed', $e->getMessage(), 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            (new LogoutAction($this->authService))->handle($request->user());

            return $this->successResponse('Logout successful',200);
        } catch (\Exception $e) {
            return $this->errorResponse('Logout failed', $e->getMessage(), 500);
        }
    }

    /**
     * Forgot password - send OTP
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            (new ForgotPasswordAction($this->authService))->handle($request->input('identifier'));

            return $this->successResponse('OTP sent successfully. Please check your email or phone.',200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send OTP', $e->getMessage(), 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $isValid = (new VerifyOtpAction($this->authService))->handle(
                $request->input('identifier'),
                $request->input('otp')
            );

            if (!$isValid) {
                return $this->errorResponse('Invalid or expired OTP', null, 400);
            }

            return $this->successResponse('OTP verified successfully',200);
        } catch (\Exception $e) {
            return $this->errorResponse('OTP verification failed', $e->getMessage(), 500);
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            (new ResetPasswordAction($this->authService))->handle(
                $request->input('identifier'),
                $request->input('otp'),
                $request->input('password')
            );

            return $this->successResponse('Password reset successfully',200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Password reset failed', $e->errors(), 400);
        } catch (\Exception $e) {
            return $this->errorResponse('Password reset failed', $e->getMessage(), 500);
        }
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        return $this->successResponse('User retrieved successfully', new UserResource($request->user()));
    }

    /**
     * Delete account
     */
    public function deleteAccount(DeleteAccountRequest $request): JsonResponse
    {
        try {
            (new DeleteAccountAction($this->authService))->handle($request->user());

            return $this->successResponse('Account deleted successfully',200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete account', $e->getMessage(), 500);
        }
    }

    /**
     * Change password for authenticated user
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            (new ChangePasswordAction())->handle(
                $request->user(),
                $request->input('password')
            );

            return $this->successResponse('Password changed successfully',200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to change password', $e->getMessage(), 500);
        }
    }
}
