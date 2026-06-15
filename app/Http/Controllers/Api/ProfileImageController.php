<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Http\Requests\UpdateProfileImageRequest;
use App\Traits\V1\ApiResponse;

class ProfileImageController extends Controller
{
    use ApiResponse;

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Store (Update) profile image.
     */
    public function store(UpdateProfileImageRequest $request): JsonResponse
    {
        $data = $this->profileService->updateProfileImage($request->user(), $request->file('image'));

        return self::successResponse('Profile image updated successfully', $data);
    }

    /**
     * Remove profile image.
     */
    public function destroy(Request $request): JsonResponse
    {
        $success = $this->profileService->deleteProfileImage($request->user());

        if (!$success) {
            return self::errorResponse('No profile image to delete', null, 404);
        }

        return self::successResponse('Profile image deleted successfully');
    }
}
