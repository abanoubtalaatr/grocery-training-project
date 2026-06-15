<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Http\Requests\UpdateProfileImageRequest;
use App\Http\Requests\UpdateProfileInfoRequest;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;

class ProfileController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Get full user profile.
     */
    public function show(Request $request): JsonResponse
    {
        $data = $this->profileService->getProfileData($request->user());
        return self::successResponse('Profile retrieved successfully', $data);
    }

    /**
     * Update profile image
     */
    public function updateImage(UpdateProfileImageRequest $request): JsonResponse
    {
        $data = $this->profileService->updateProfileImage($request->user(), $request->file('image'));

        return self::successResponse('Profile image updated successfully', $data);
    }

    /**
     * Update profile information
     */
    public function updateInfo(UpdateProfileInfoRequest $request): JsonResponse
    {
        $user = $this->profileService->updateProfileInfo($request->user(), $request->validated());

        return self::successResponse('Profile updated successfully', [
            'id' => $user->id,
            'username' => $user->username,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'full_name' => $user->full_name,
            'gender' => $user->gender,
            'birthday' => $user->birthday?->format('Y-m-d'),
            'email' => $user->email,
            'phone' => $user->phone,
            'country_code' => $user->country_code,
            'preferred_languages' => $user->preferred_languages ?? [],
            'profile_image_url' => $user->profile_image_url,
            'updated_at' => $user->updated_at,
        ]);
    }

    /**
     * Delete profile image
     */
    public function deleteImage(Request $request): JsonResponse
    {
        $success = $this->profileService->deleteProfileImage($request->user());

        if (!$success) {
            return self::errorResponse('No profile image to delete', null, 404);
        }

        return self::successResponse('Profile image deleted successfully');
    }

    /**
     * List active sessions/devices.
     */
    public function sessions(Request $request): JsonResponse
    {
        $tokens = $this->profileService->getActiveSessions($request->user());
        return self::collectionResponse('Sessions retrieved successfully', $tokens);
    }

    /**
     * Revoke a session/device.
     */
    public function destroySession(Request $request, string $tokenId): JsonResponse
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
