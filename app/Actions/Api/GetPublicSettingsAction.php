<?php

namespace App\Actions\Api;

use App\Models\Setting;

class GetPublicSettingsAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        $settings = Setting::getSettings();

        return [
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
        ];
    }
}
