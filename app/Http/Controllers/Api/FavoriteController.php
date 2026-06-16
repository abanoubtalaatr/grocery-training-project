<?php

namespace App\Http\Controllers\Api;

use App\Actions\Favorite\CheckFavoriteAction;
use App\Actions\Favorite\GetFavoritesAction;
use App\Actions\Favorite\RemoveFavoriteAction;
use App\Actions\Favorite\ToggleFavoriteAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteMealResource;
use App\Models\Meal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request, GetFavoritesAction $action): JsonResponse
    {
        $favorites = $action->execute($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Favorites retrieved successfully',
            'data' => FavoriteMealResource::collection($favorites),
            'total_count' => $favorites->count(),
        ]);
    }

    public function toggle(
        Request $request,
        Meal $meal,
        ToggleFavoriteAction $action
    ): JsonResponse {
        $isFavorited = $action->execute($request->user(), $meal);

        return response()->json([
            'success' => true,
            'message' => $isFavorited ? 'Added to favorites' : 'Removed from favorites',
            'data' => [
                'meal_id' => $meal->id,
                'is_favorited' => $isFavorited,
            ],
        ]);
    }

    public function check(
        Request $request,
        Meal $meal,
        CheckFavoriteAction $action
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'data' => [
                'meal_id' => $meal->id,
                'is_favorited' => $action->execute($request->user(), $meal),
            ],
        ]);
    }

    public function remove(
        Request $request,
        Meal $meal,
        RemoveFavoriteAction $action
    ): JsonResponse {
        $action->execute($request->user(), $meal);

        return response()->json([
            'success' => true,
            'message' => 'Removed from favorites',
            'data' => [
                'meal_id' => $meal->id,
                'is_favorited' => false,
            ],
        ]);
    }
}
