<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileImageRequest;
use App\Http\Requests\Profile\UpdateProfileInfoRequest;
use App\Actions\Profile\GetFullProfileAction;
use App\Actions\Profile\UpdateProfileImageAction;
use App\Actions\Profile\UpdateProfileInfoAction;
use App\Actions\Profile\DeleteProfileImageAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request, GetFullProfileAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $request->user()->currentAccessToken()?->id);

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateImage(UpdateProfileImageRequest $request, UpdateProfileImageAction $action): JsonResponse
    {
        try {
            $data = $action($request->user(), $request->file('image'));

            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile image',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateInfo(UpdateProfileInfoRequest $request, UpdateProfileInfoAction $action): JsonResponse
    {
        try {
            $data = $request->only(['username', 'firstname', 'lastname', 'gender', 'birthday', 'email', 'phone', 'country_code', 'preferred_languages']);
            
            // To ensure preferred_languages is preserved if passed
            if ($request->has('preferred_languages')) {
                $data['preferred_languages'] = $request->preferred_languages ?? [];
            }

            $updatedData = $action($request->user(), $data);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $updatedData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteImage(Request $request, DeleteProfileImageAction $action): JsonResponse
    {
        try {
            $deleted = $action($request->user());

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'No profile image to delete',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile image deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profile image',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sessions(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $user->currentAccessToken()?->id;

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
}
