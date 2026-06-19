<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use App\Services\Admin\SettingService;
use App\Http\Requests\Admin\UpdateSettingRequest;

class SettingController extends Controller
{
    public function __construct(
        private SettingService $settingService
    ) {}

    public function edit()
    {
        $setting = $this->settingService->getSettings();

        return view(
            'admin.settings.edit',
            compact('setting')
        );
    }

    public function update(
    UpdateSettingRequest $request
    ) {
        $this->settingService->update(
            Setting::getSettings(),
            $request->validated()
        );

        return back()->with(
            'success',
            'Settings updated successfully.'
        );
    }
}