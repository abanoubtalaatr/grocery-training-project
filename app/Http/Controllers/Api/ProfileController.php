<?php

namespace App\Http\Controllers\Api;

use App\Actions\Profile\DeleteProfileImageAction;
use App\Actions\Profile\DestroySessionAction;
use App\Actions\Profile\UpdateProfileImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileImageRequest;
use App\Http\Requests\UpdateProfileInfoRequest;
use App\Http\Resources\ActiveSessionResource;
use App\Http\Resources\UserProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Get full user profile dashboard.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load(['addresses', 'favorites.meal.category', 'favorites.meal.subcategory']);

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data' => new UserProfileResource($user),
        ]);
    }

    /**
     * Update profile image.
     */
    public function updateImage(UpdateProfileImageRequest $request): JsonResponse
    {
        $data = UpdateProfileImageAction::run($request->user(), $request->file('image'));

        return response()->json([
            'success' => true,
            'message' => 'Profile image updated successfully',
            'data' => $data,
        ]);
    }

    /**
     * Update profile information.
     */
    public function updateInfo(UpdateProfileInfoRequest $request): JsonResponse
    {
        $user = $request->user();

        // Update only validated fields that are present
        $data = $request->only(['username', 'firstname', 'lastname', 'gender', 'birthday', 'email', 'phone', 'country_code', 'preferred_languages']);

        if ($request->has('preferred_languages')) {
            $data['preferred_languages'] = $request->preferred_languages ?? [];
        }

        $data = array_filter($data, function ($value, $key) {
            if ($key === 'preferred_languages') {
                return true;
            }
            return $value !== null && $value !== '';
        }, ARRAY_FILTER_USE_BOTH);

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided to update',
            ], 400);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
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
            ],
        ]);
    }

    /**
     * Delete profile image.
     */
    public function deleteImage(Request $request): JsonResponse
    {
        DeleteProfileImageAction::run($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Profile image deleted successfully',
        ]);
    }

    /**
     * List active sessions/devices.
     */
    public function sessions(Request $request): JsonResponse
    {
        $tokens = $request->user()->tokens()->get();

        return response()->json([
            'success' => true,
            'message' => 'Sessions retrieved successfully',
            'data' => ActiveSessionResource::collection($tokens),
        ]);
    }

    /**
     * Revoke a session/device.
     */
    public function destroySession(Request $request, string $tokenId): JsonResponse
    {
        DestroySessionAction::run($request->user(), $tokenId);

        return response()->json([
            'success' => true,
            'message' => 'Session revoked successfully',
        ]);
    }
}
