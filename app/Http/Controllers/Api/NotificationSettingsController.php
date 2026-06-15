<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateNotificationCategoryRequest;
use App\Http\Requests\Api\UpdateNotificationSettingsRequest;
use App\Models\UserNotificationSetting;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationSettingsController extends Controller
{
    use ApiResponse;

    /**
     * Get user notification settings
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $settings = $user->initializeNotificationSettings();

            return self::successResponse(
                'Notification settings retrieved successfully',
                $settings ? $this->formatSettings($settings) : $this->defaultSettingsStructure()
            );
        } catch (\Throwable $e) {
            return self::successResponse(
                'Notification settings retrieved (default)',
                $this->defaultSettingsStructure()
            );
        }
    }

    /**
     * Update notification settings.
     */
    public function update(UpdateNotificationSettingsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = Auth::user();
        $settings = $user->initializeNotificationSettings();
        $settings->update($validated);

        return self::successResponse(
            'Notification settings updated successfully',
            $this->formatSettings($settings->fresh())
        );
    }

    /**
     * Update specific category settings.
     */
    public function updateCategory(UpdateNotificationCategoryRequest $request, string $category): JsonResponse
    {
        $validated = $request->validated();

        $user = Auth::user();
        $settings = $user->initializeNotificationSettings();

        $fields = $this->getCategoryFields($category);

        if (empty($fields)) {
            return self::errorResponse('Invalid category', null, 400);
        }

        $updateData = [];
        foreach ($fields as $field) {
            $updateData[$field] = (bool) $validated['enabled'];
        }

        $settings->update($updateData);

        return self::successResponse(
            'Notification category settings updated successfully',
            $this->formatSettings($settings->fresh())
        );
    }

    /**
     * Default settings structure (matches migration defaults) when no record exists or on error.
     */
    private function defaultSettingsStructure(): array
    {
        return [
            'order_delivery_updates' => [
                'category' => 'Order & Delivery Updates',
                'enabled' => true,
                'settings' => [
                    'order_confirmation' => true,
                    'order_shipped' => true,
                    'delivery_updates' => true,
                    'out_of_stock_alerts' => true,
                ],
            ],
            'deals_promotions' => [
                'category' => 'Deals & Promotions',
                'enabled' => true,
                'settings' => [
                    'weekly_discounts' => true,
                    'exclusive_member_offers' => true,
                    'seasonal_campaigns' => true,
                ],
            ],
            'account_reminders' => [
                'category' => 'Account & Reminders',
                'enabled' => true,
                'settings' => [
                    'cart_reminders' => true,
                    'payment_billing' => true,
                ],
            ],
            'channels' => [
                'category' => 'Notification Channels',
                'enabled' => true,
                'settings' => [
                    'email_notifications' => true,
                    'push_notifications' => true,
                    'sms_notifications' => false,
                ],
            ],
        ];
    }

    /**
     * Format settings for response
     */
    private function formatSettings(UserNotificationSetting $settings)
    {
        return [
            'order_delivery_updates' => [
                'category' => 'Order & Delivery Updates',
                'enabled' => $settings->order_confirmation || $settings->order_shipped || $settings->delivery_updates || $settings->out_of_stock_alerts,
                'settings' => [
                    'order_confirmation' => $settings->order_confirmation,
                    'order_shipped' => $settings->order_shipped,
                    'delivery_updates' => $settings->delivery_updates,
                    'out_of_stock_alerts' => $settings->out_of_stock_alerts,
                ]
            ],
            'deals_promotions' => [
                'category' => 'Deals & Promotions',
                'enabled' => $settings->weekly_discounts || $settings->exclusive_member_offers || $settings->seasonal_campaigns,
                'settings' => [
                    'weekly_discounts' => $settings->weekly_discounts,
                    'exclusive_member_offers' => $settings->exclusive_member_offers,
                    'seasonal_campaigns' => $settings->seasonal_campaigns,
                ]
            ],
            'account_reminders' => [
                'category' => 'Account & Reminders',
                'enabled' => $settings->cart_reminders || $settings->payment_billing,
                'settings' => [
                    'cart_reminders' => $settings->cart_reminders,
                    'payment_billing' => $settings->payment_billing,
                ]
            ],
            'channels' => [
                'category' => 'Notification Channels',
                'enabled' => $settings->email_notifications || $settings->push_notifications || $settings->sms_notifications,
                'settings' => [
                    'email_notifications' => $settings->email_notifications,
                    'push_notifications' => $settings->push_notifications,
                    'sms_notifications' => $settings->sms_notifications,
                ]
            ]
        ];
    }

    /**
     * Get fields for a category
     */
    private function getCategoryFields(string $category): array
    {
        $categories = [
            'order_delivery' => ['order_confirmation', 'order_shipped', 'delivery_updates', 'out_of_stock_alerts'],
            'deals_promotions' => ['weekly_discounts', 'exclusive_member_offers', 'seasonal_campaigns'],
            'account_reminders' => ['cart_reminders', 'payment_billing'],
            'channels' => ['email_notifications', 'push_notifications', 'sms_notifications'],
        ];

        return $categories[$category] ?? [];
    }
}
