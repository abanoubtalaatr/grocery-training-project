<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WebAuthController extends Controller
{
    public function showRegister()
    {

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'userName' => 'required | string | max:255',
            'email' => 'required | email | unique:users',
            'phone' => 'required | max:20 ',
            'password' => 'required | min:6 | confirmed ',
        ]);

        // Create user
        $user = User::create([
            'username' => $request->userName,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            // 'agree_terms' => $data['agree_terms'],
        ]);

        Auth::login($user);
        return redirect('dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'email' => ' required | email',
            'password' => ' required'
        ]);

        if (Auth::attempt($user)) {
            $request->session()->regenerate();
            return redirect('dashboard');
        }

        return back()->withErrors([
            'email' => "This cannot match any record"
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
