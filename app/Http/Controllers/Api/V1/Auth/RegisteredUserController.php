<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected AuthService $authService
    ) {}

    public function store(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());
            return $this->successResponse('Registration successful', [
                'user' => [
                    'id' => $result['user']->id,
                    'username' => $result['user']->username,
                    'email' => $result['user']->email,
                    'phone' => $result['user']->phone,
                ],
                'token' => $result['token'],
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Registration failed', $e->getMessage(), 500);
        }
    }
}
