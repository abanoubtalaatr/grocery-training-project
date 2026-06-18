<?php

namespace App\Services\Admin;

use App\Models\Setting;

class SettingService
{
    public function get(): Setting
    {
        return Setting::getSettings();
    }

    public function update(array $data): Setting
    {
        $settings = Setting::getSettings();
        $settings->update($data);
        return $settings;
    }
}
