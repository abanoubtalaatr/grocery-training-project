<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MealService;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function __construct(
        private MealService $mealService
    ) {}

    public function index(Request $request)
    {
        $meals = $this->mealService->paginate(
            search: $request->search
        );

        return view(
            'admin.meals.index',
            compact('meals')
        );
    }
}