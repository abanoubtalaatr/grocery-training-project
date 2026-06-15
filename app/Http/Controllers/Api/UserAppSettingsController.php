<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAppearanceRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\UpdateNotificationPreferencesRequest;
use App\Services\UserAppSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAppSettingsController extends Controller
{
    public function __construct(
        private readonly UserAppSettingsService $settingsService,
    ) {}

    /**
     * Show preferred application language.
     */
    public function showLanguage(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->settingsService->getLanguage($request->user()),
        ]);
    }

    /**
     * Update preferred application language.
     */
    public function updateLanguage(UpdateLanguageRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Language updated successfully',
            'data' => $this->settingsService->updateLanguage(
                $request->user(),
                (string) $request->validated('language'),
            ),
        ]);
    }

    /**
     * Show preferred theme appearance.
     */
    public function showAppearance(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->settingsService->getAppearance($request->user()),
        ]);
    }

    /**
     * Update preferred theme appearance.
     */
    public function updateAppearance(UpdateAppearanceRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Appearance updated successfully',
            'data' => $this->settingsService->updateAppearance(
                $request->user(),
                (string) $request->validated('theme'),
            ),
        ]);
    }

    /**
     * Show user notification subscription preferences.
     */
    public function showNotificationPreferences(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->settingsService->getNotificationPreferences($request->user()),
        ]);
    }

    /**
     * Update user notification subscription preferences.
     */
    public function updateNotificationPreferences(UpdateNotificationPreferencesRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Notification preferences updated successfully',
            'data' => $this->settingsService->updateNotificationPreferences(
                $request->user(),
                $request->validated(),
            ),
        ]);
    }
}
