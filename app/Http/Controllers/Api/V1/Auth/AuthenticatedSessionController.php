<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Traits\V1\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    use ApiResponse;
    
    /**
     * Constructor
     * Note: Authenticated Session Controller because we are using Sanctum for authentication
     */
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Login authenticated user
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->login($request->input('login'), $request->input('password'));
            return $this->successResponse('Login successful', [
                'user' => $user['user'],
                'token' => $user['token'],
            ]);


        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Login failed', $e->errors(), 401);
        } catch (\Exception $e) {
            return $this->errorResponse('Login failed', 'Internal server error', 500);
        }
    }


    /**
     * Logout authenticated user
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());
            return $this->successResponse('Logout successful');
        } catch (\Exception $e) {
            return $this->errorResponse('Logout failed', 'Internal server error', 500);
        }
    }
}
