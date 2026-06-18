<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Services\Admin\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $settings = $this->service->get();
        return view('admin.settings.index', compact('settings'));
    }

    public function edit()
    {
        $settings = $this->service->get();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        $settings = $this->service->get();

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            // delete old logo
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            // delete old favicon
            if ($settings->favicon && Storage::disk('public')->exists($settings->favicon)) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $this->service->update($data);

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated');
    }
}
