<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleSwitcherController extends Controller
{
    private const SUPPORTED = ['en', 'ar'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, self::SUPPORTED, true)) {
            Session::put('locale', $locale);
        }

        return redirect()->back()->withHeaders([
            'Vary' => 'Accept-Language',
        ]);
    }
}
