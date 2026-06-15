<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateAppearanceRequest;
use App\Http\Requests\Api\UpdateLanguageRequest;
use App\Http\Requests\Api\UpdateUserNotificationPreferencesRequest;
use App\Services\UserAppSettingsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAppSettingsController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UserAppSettingsService $settingsService,
    ) {}

    public function showLanguage(Request $request): JsonResponse
    {
        return $this->successResponse($this->settingsService->getLanguage($request->user()));
    }

    public function updateLanguage(UpdateLanguageRequest $request): JsonResponse
    {
        return $this->successResponse(
            $this->settingsService->updateLanguage(
                $request->user(),
                (string) $request->input('language'),
            ),
            'Language updated successfully',
        );
    }

    public function showAppearance(Request $request): JsonResponse
    {
        return $this->successResponse($this->settingsService->getAppearance($request->user()));
    }

    public function updateAppearance(UpdateAppearanceRequest $request): JsonResponse
    {
        return $this->successResponse(
            $this->settingsService->updateAppearance(
                $request->user(),
                (string) $request->input('theme'),
            ),
            'Appearance updated successfully',
        );
    }

    public function showNotificationPreferences(Request $request): JsonResponse
    {
        return $this->successResponse($this->settingsService->getNotificationPreferences($request->user()));
    }

    public function updateNotificationPreferences(UpdateUserNotificationPreferencesRequest $request): JsonResponse
    {
        return $this->successResponse(
            $this->settingsService->updateNotificationPreferences(
                $request->user(),
                $request->validated(),
            ),
            'Notification preferences updated successfully',
        );
    }
}
