<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\MealResource;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index(Request $request)
    {
        $meals = Meal::query()->filter($request->all())->get();

        return ApiResponse::success(MealResource::collection($meals));
    }
}
