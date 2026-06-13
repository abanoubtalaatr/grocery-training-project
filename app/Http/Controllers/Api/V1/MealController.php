<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
class MealController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $meals = Meal::query()->filter($request)->get();

        return $this->success($meals, 'Meals retrieved successfully');
    }
}
