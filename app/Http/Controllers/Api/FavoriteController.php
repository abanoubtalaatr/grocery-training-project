<?php

namespace App\Http\Controllers\Api;

use App\Actions\Favorite\ToggleFavoriteAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\Models\Meal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Get all user's favorite meals
     */
    public function index(Request $request): JsonResponse
    {
        $favorites = $request->user()->favorites()
            ->with(['meal.category', 'meal.subcategory'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Favorites retrieved successfully',
            'data' => FavoriteResource::collection($favorites),
            'total_count' => $favorites->count(),
        ]);
    }

    /**
     * Toggle favorite status for a meal
     */
    public function toggle(Request $request, Meal $meal): JsonResponse
    {
        $result = ToggleFavoriteAction::run($request->user(), $meal);

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => [
                'meal_id' => $meal->id,
                'is_favorited' => $result['is_favorited'],
            ],
        ]);
    }

    /**
     * Check if a meal is favorited
     */
    public function check(Request $request, Meal $meal): JsonResponse
    {
        $isFavorited = $request->user()->favorites()->where('meal_id', $meal->id)->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'meal_id' => $meal->id,
                'is_favorited' => $isFavorited,
            ],
        ]);
    }

    /**
     * Remove meal from favorites
     */
    public function remove(Request $request, Meal $meal): JsonResponse
    {
        $deleted = $request->user()->favorites()->where('meal_id', $meal->id)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Removed from favorites',
                'data' => [
                    'meal_id' => $meal->id,
                    'is_favorited' => false,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Meal was not in favorites',
        ], 404);
    }
}
