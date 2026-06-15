<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService) {}

    public function store(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return self::successResponse('Registration successful', [
            'user' => [
                'id' => $result['user']->id,
                'username' => $result['user']->username,
                'email' => $result['user']->email,
                'phone' => $result['user']->phone,
                'created_at' => $result['user']->created_at,
            ],
            'token' => $result['token'],
        ], 201);
    }
}
