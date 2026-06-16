<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meal;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::with([
            'category',
            'subcategory',
        ])
        ->latest()
        ->paginate(10);

        return view(
            'admin.meals.index',
            compact('meals')
        );
    }
}