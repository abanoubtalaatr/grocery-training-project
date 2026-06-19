<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    public function switch(string $locale)
    {
        if (! in_array($locale, ['ar', 'en'])) {
            abort(404);
        }

        session(['locale' => $locale]);

        return back();
    }
}