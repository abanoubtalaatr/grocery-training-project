<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Rules\UsernameMustContainLetter;
use App\Support\EgyptianPhoneRules;
use App\Support\EmailValidation;
use App\Actions\Profile\UpdateProfileAction;
use App\Actions\Profile\UpdateProfileImageAction;
use App\Actions\Profile\DeleteProfileImageAction;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UpdateProfileImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private const PROFILE_SINGLE_IMAGE_MESSAGE = 'Only one profile image is allowed';

    /**
     * Get full user profile: picture, name, gender, birthday, addresses,
     * order history, in-progress orders with tracking, order notifications,
     * settings (sessions), wishlist.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load(['addresses', 'favorites.meal.category', 'favorites.meal.subcategory']);

        $user->load(['addresses', 'favorites.meal.category', 'favorites.meal.subcategory']);

        return response()->json(['success' => true, 'message' => 'Profile retrieved successfully', 'data' => new \App\Http\Resources\ProfileResource($user)]);
    }

    /**
     * Update profile image
     */
    public function updateImage(UpdateProfileImageRequest $request, UpdateProfileImageAction $action): JsonResponse
    {
        if (count($request->allFiles()) > 1) {
            return response()->json(['success' => false, 'message' => self::PROFILE_SINGLE_IMAGE_MESSAGE, 'errors' => ['image' => [self::PROFILE_SINGLE_IMAGE_MESSAGE]]], 422);
        }

        $uploaded = $request->file('image');
        if (is_array($uploaded)) {
            return response()->json(['success' => false, 'message' => self::PROFILE_SINGLE_IMAGE_MESSAGE, 'errors' => ['image' => [self::PROFILE_SINGLE_IMAGE_MESSAGE]]], 422);
        }

        $request->validate(['image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']]);

        $user = $request->user();

        $user = $action->execute($user, $request->file('image'));

        return response()->json(['success' => true, 'message' => 'Profile image updated successfully', 'data' => ['profile_image' => $user->profile_image, 'profile_image_url' => $user->profile_image_url]]);
    }

    /**
     * Update profile information
     */
    public function updateInfo(UpdateProfileRequest $request, UpdateProfileAction $action): JsonResponse
    {
        $user = $request->user();

        if ($request->has('phone') && is_string($request->input('phone'))) {
            $request->merge(['phone' => preg_replace('/\s+/', '', $request->input('phone'))]);
        }

        $data = $request->validated();

        $user = $action->execute($user, $data);

        return response()->json(['success' => true, 'message' => 'Profile updated successfully', 'data' => ['id' => $user->id, 'username' => $user->username, 'firstname' => $user->firstname, 'lastname' => $user->lastname, 'full_name' => $user->full_name, 'gender' => $user->gender, 'birthday' => $user->birthday?->format('Y-m-d'), 'email' => $user->email, 'phone' => $user->phone, 'country_code' => $user->country_code, 'preferred_languages' => $user->preferred_languages ?? [], 'profile_image_url' => $user->profile_image_url, 'updated_at' => $user->updated_at]]);
    }

    /**
     * Delete profile image
     */
    public function deleteImage(Request $request, DeleteProfileImageAction $action): JsonResponse
    {
        $user = $request->user();

        if (! $user->profile_image) {
            return response()->json(['success' => false, 'message' => 'No profile image to delete'], 404);
        }

        $action->execute($user);

        return response()->json(['success' => true, 'message' => 'Profile image deleted successfully']);
    }

    /**
     * List active sessions/devices (Sanctum tokens). User can logout from each.
     */
    public function sessions(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $request->user()->currentAccessToken()?->id;

        $tokens = $user->tokens()->get()->map(function ($token) use ($currentTokenId) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at?->toIso8601String(),
                'is_current' => (string) $token->id === (string) $currentTokenId,
                'created_at' => $token->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Sessions retrieved successfully',
            'data' => $tokens,
        ]);
    }

    /**
     * Revoke a session/device (logout from that token).
     */
    public function destroySession(Request $request, string $tokenId): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $user->currentAccessToken()?->id;

        if ((string) $tokenId === (string) $currentTokenId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot revoke your current session from this request. Use logout instead.',
            ], 400);
        }

        $token = $user->tokens()->find($tokenId);
        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found',
            ], 404);
        }

        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Session revoked successfully',
        ]);
    }

    // Formatting moved to Resources; controller kept minimal
}
