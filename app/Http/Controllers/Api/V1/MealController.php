<?php

namespace App\Http\Controllers\Api\V1;


use App\Actions\Api\V1\ListMealsAction;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Meal;

class MealController extends Controller
{
    public function index(Request $request)
    {
        $meals = Meal::query()->filter($request);

        return response()->json($meals->get());
    }
}
