<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Display security settings page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $sessions = $this->profileService->getSessions($user);

        return view('dashboard.security', compact('user', 'sessions'));
    }

    /**
     * Update user password.
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $this->profileService->updatePassword($user, $request->input('password'));

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
