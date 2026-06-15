<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserAppSettingsService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAppSettingsController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly UserAppSettingsService $settingsService,
    ) {}

    public function showLanguage(Request $request): JsonResponse
    {
        return self::successResponse(
            'Language settings retrieved successfully',
            $this->settingsService->getLanguage($request->user())
        );
    }

    public function updateLanguage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'language' => ['required', 'string', 'in:en,ar'],
        ]);

        if ($validator->fails()) {
            return self::errorResponse('Validation failed', $validator->errors(), 422);
        }

        $data = $this->settingsService->updateLanguage(
            $request->user(),
            (string) $request->input('language'),
        );

        return self::successResponse('Language updated successfully', $data);
    }

    public function showAppearance(Request $request): JsonResponse
    {
        return self::successResponse(
            'Appearance settings retrieved successfully',
            $this->settingsService->getAppearance($request->user())
        );
    }

    public function updateAppearance(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'theme' => ['required', 'string', 'in:light,dark'],
        ]);

        if ($validator->fails()) {
            return self::errorResponse('Validation failed', $validator->errors(), 422);
        }

        $data = $this->settingsService->updateAppearance(
            $request->user(),
            (string) $request->input('theme'),
        );

        return self::successResponse('Appearance updated successfully', $data);
    }

    public function showNotificationPreferences(Request $request): JsonResponse
    {
        return self::successResponse(
            'Notification preferences retrieved successfully',
            $this->settingsService->getNotificationPreferences($request->user())
        );
    }

    public function updateNotificationPreferences(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_updates' => ['sometimes', 'boolean'],
            'promotion_emails' => ['sometimes', 'boolean'],
            'nutrition_insights' => ['sometimes', 'boolean'],
            'price_alerts' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            return self::errorResponse('Validation failed', $validator->errors(), 422);
        }

        $data = $this->settingsService->updateNotificationPreferences(
            $request->user(),
            $validator->validated(),
        );

        return self::successResponse('Notification preferences updated successfully', $data);
    }
}
