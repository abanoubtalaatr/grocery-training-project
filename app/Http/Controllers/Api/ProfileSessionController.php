<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;

class ProfileSessionController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display a listing of active sessions.
     */
    public function index(Request $request): JsonResponse
    {
        $tokens = $this->profileService->getActiveSessions($request->user());
        return self::collectionResponse('Sessions retrieved successfully', $tokens);
    }

    /**
     * Remove the specified session.
     */
    public function destroy(Request $request, string $tokenId): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $user->currentAccessToken()?->id;

        if ((string) $tokenId === (string) $currentTokenId) {
            return self::errorResponse('Cannot revoke your current session from this request. Use logout instead.', null, 400);
        }

        $success = $this->profileService->revokeSession($user, $tokenId);

        if (!$success) {
            return self::errorResponse('Session not found', null, 404);
        }

        return self::successResponse('Session revoked successfully');
    }
}
