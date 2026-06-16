<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->route('admins.dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
    }
}
