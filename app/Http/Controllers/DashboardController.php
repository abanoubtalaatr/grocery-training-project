<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $mealsCount = \App\Models\Meal::count();
        $usersCount = \App\Models\User::count();
        $reviewsCount = \App\Models\Review::count();
        $target = 1000; // Example target for meals
        return view('admins.dashboard', compact('mealsCount','target', 'usersCount', 'reviewsCount'));
    }
}
