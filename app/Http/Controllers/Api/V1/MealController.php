<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index(Request $request)
    {
        $meals = Meal::query()->filter($request);


        return response()->json($meals->get());
    }
}
