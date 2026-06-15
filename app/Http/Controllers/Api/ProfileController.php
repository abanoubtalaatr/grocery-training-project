<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ProfileService;
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
     * Display the authenticated user's profile.
     */
    public function show(Request $request): JsonResponse
    {
        $data = $this->profileService->getProfileData($request->user());
        return self::successResponse('Profile retrieved successfully', $data);
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(UpdateProfileInfoRequest $request): JsonResponse
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
}
