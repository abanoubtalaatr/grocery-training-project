<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService) {}

    /**
     * Login
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->input('login'),
            $request->input('password')
        );

        return self::successResponse('Login successful', [
            'user' => [
                'id' => $result['user']->id,
                'username' => $result['user']->username,
                'email' => $result['user']->email,
                'phone' => $result['user']->phone,
            ],
            'token' => $result['token'],
        ]);
    }

    /**
     * Logout
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        return self::successResponse('Logout successful');
    }
}
