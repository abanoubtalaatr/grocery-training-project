<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meal;

class MealController extends Controller
{
    public function index(Request $request)
    {
        $meals = Meal::query()->filter($request);
        return response()->json($meals->paginate($request->input('per_page', 5)));
    }
}
