<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class PersonalInfoController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Display personal info dashboard page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        return view('dashboard.personal-info', compact('user'));
    }

    /**
     * Update personal info.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $this->profileService->updatePersonalInfo($user, $request->validated());

        return redirect()->back()->with('success', 'Personal information updated successfully.');
    }
}
