<?php

namespace App\Services\Admin;

use App\Models\Setting;

class SettingService
{
    public function getSettings(): Setting
    {
        return Setting::getSettings();
    }

    public function update(
        Setting $setting,
        array $data
    ): bool {
        return $setting->update($data);
    }
}