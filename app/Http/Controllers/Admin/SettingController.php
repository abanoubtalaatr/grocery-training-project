<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => Setting::getSettings(),
        ]);
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        Setting::getSettings()->update($request->validated());

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully.');
    }
}
