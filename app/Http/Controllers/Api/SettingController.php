<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    use ApiResponse;

    /**
     * Get settings
     */
    public function index(): JsonResponse
    {
        $settings = Setting::getSettings();
        return self::successResponse('Settings retrieved successfully', new SettingResource($settings));
    }

    /**
     * Update settings
     */
    public function update($request): JsonResponse
    {
        $settings = Setting::getSettings();

        $data = $request->validated();

        // Handle file uploads if needed
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $settings->update($data);

        return self::successResponse('Settings updated successfully', new SettingResource($settings));
    }

    /**
     * Get specific settings for public use
     */
    public function publicSettings(): JsonResponse
    {
        $settings = Setting::getSettings();

        return self::successResponse('Public settings retrieved successfully', [
            'site_name' => $settings->site_name,
            'site_description' => $settings->site_description,
            'social_media' => [
                'facebook' => $settings->facebook,
                'linkedin' => $settings->linkedin,
                'instagram' => $settings->instagram,
                'twitter' => $settings->twitter,
            ],
            'contact' => [
                'email' => $settings->email,
                'phone' => $settings->phone,
                'address' => $settings->address,
            ],
            'logo' => $settings->logo,
            'copyright' => $settings->copyright_text,
        ]);
    }
}
