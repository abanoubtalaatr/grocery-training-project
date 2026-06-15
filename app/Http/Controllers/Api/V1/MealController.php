<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    use ApiResponseCollection;

    public function index(Request $request): JsonResponse
    {
        $meals = Meal::query()->filter($request)->get();

        return self::collectionResponse('Meals retrieved successfully', $meals);
    }
}
