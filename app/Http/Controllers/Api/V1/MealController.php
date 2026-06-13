<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meal;

class MealController extends Controller
{
    public function index(Request $request)
    {
        $meals = Meal::query()
            ->filter($request)
            ->paginate(5);

        return response()->json($meals);
    }
}
