<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\V1\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthenticatedUserController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected AuthService $authService
    ) {}



    /**
     * Show authenticated user
     */
    public function show(Request $request): JsonResponse
    {
        return $this->successResponse('User retrieved successfully', [
            'user' => [
                'id' => $request->user()->id,
                'username' => $request->user()->username,
                'email' => $request->user()->email,
                'phone' => $request->user()->phone,
            ],
        ]);
    }


    /**
     * Delete authenticated user
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $this->authService->deleteAccount($request->user());
            return $this->successResponse('Account deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Account deletion failed', 'Internal server error', 500);
        }
    }
}
