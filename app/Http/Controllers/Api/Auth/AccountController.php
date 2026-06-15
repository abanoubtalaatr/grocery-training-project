<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthService $authService) {}

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        return self::successResponse('User retrieved successfully', [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'email_verified' => $user->email_verified,
                'phone_verified' => $user->phone_verified,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->authService->deleteAccount($request->user());
        return self::successResponse('Account deleted successfully');
    }
}
