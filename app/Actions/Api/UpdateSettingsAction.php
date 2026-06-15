<?php

namespace App\Actions\Api;

use App\Models\Setting;
use Illuminate\Http\Request;

class UpdateSettingsAction
{
    public function execute(Request $request): Setting
    {
        $settings = Setting::getSettings();
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $settings->update($data);

        return $settings;
    }
}
