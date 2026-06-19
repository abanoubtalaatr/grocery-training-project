<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;

class PersonalInfoController extends Controller
{
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
        
        $data = $request->validated();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\s+/', '', $data['phone']);
        }
        
        $user->update($data);
        
        return redirect()->back()->with('success', 'Personal information updated successfully.');
    }
}
