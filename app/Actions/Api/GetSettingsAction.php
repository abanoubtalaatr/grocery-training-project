<?php

namespace App\Actions\Api;

use App\Models\Setting;

class GetSettingsAction
{
    public function execute(): Setting
    {
        return Setting::getSettings();
    }
}
