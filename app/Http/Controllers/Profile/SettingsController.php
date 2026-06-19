<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserSettingsUpdateRequest;
use App\Services\UserAppSettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(
        private readonly UserAppSettingsService $settingsService,
    ) {}

    /**
     * Display settings page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $preferences = $this->settingsService->getNotificationPreferences($user);
        $language = $this->settingsService->getLanguage($user)['language'];
        $theme = $this->settingsService->getAppearance($user)['theme'];

        return view('dashboard.settings', compact('user', 'preferences', 'language', 'theme'));
    }

    /**
     * Update settings.
     */
    public function update(UserSettingsUpdateRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $this->settingsService->updateLanguage($user, $request->input('language'));
        $this->settingsService->updateAppearance($user, $request->input('theme'));
        
        $this->settingsService->updateNotificationPreferences($user, [
            'order_updates' => $request->boolean('order_updates'),
            'promotion_emails' => $request->boolean('promotion_emails'),
            'nutrition_insights' => $request->boolean('nutrition_insights'),
            'price_alerts' => $request->boolean('price_alerts'),
        ]);

        return redirect()->back()->with('success', 'Preferences updated successfully.');
    }
}
