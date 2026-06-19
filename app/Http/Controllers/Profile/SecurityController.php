<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    /**
     * Display security settings page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        $sessions = $user->tokens()->get();

        return view('dashboard.security', compact('user', 'sessions'));
    }

    /**
     * Update user password.
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
