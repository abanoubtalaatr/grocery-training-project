<?php

namespace App\Http\Controllers\Api;

use App\Actions\Favorite\ToggleFavoriteAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FavoriteMealResource;
use App\Models\Meal;
use App\Traits\ApiResponse;
use Illuminate\Http\{JsonResponse, Request};

class FavoriteController extends Controller
{
    use ApiResponse;

   
  public function index(Request $request): JsonResponse
{
    $favorites = $request->user()->favorites()->with(['meal.category', 'meal.subcategory'])->latest()->get();

    return $this->successResponse(
        data: FavoriteMealResource::collection($favorites)->resolve(), 
        message: 'Favorites retrieved successfully'
    );
}

   
    public function toggle(Request $request, Meal $meal, ToggleFavoriteAction $action): JsonResponse
    {
        $result = $action->toggle($request->user(), $meal);

        return $this->successResponse(
            data: ['meal_id' => $meal->id, 'is_favorited' => $result['status']],
            message: $result['message']
        );
    }

  
    public function check(Request $request, Meal $meal): JsonResponse
    {
        return $this->successResponse([
            'meal_id' => $meal->id,
            'is_favorited' => $request->user()->favorites()->where('meal_id', $meal->id)->exists()
        ]);
    }


    public function remove(Request $request, Meal $meal): JsonResponse
    {
        $deleted = $request->user()->favorites()->where('meal_id', $meal->id)->delete();

        return $deleted 
            ? $this->successResponse(['meal_id' => $meal->id, 'is_favorited' => false], 'Removed from favorites')
            : $this->errorResponse('Meal was not in favorites', 404);
    }
}