<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Favorite\GetFavoritesAction;
use App\Actions\Favorite\ToggleFavoriteAction;
use App\Actions\Favorite\CheckFavoriteAction;
use App\Actions\Favorite\RemoveFavoriteAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request, GetFavoritesAction $action): JsonResponse
    {
        try {
            $favorites = $action($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Favorites retrieved successfully',
                'data' => $favorites,
                'total_count' => $favorites->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve favorites',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function toggle(Request $request, string $mealId, ToggleFavoriteAction $action): JsonResponse
    {
        try {
            $result = $action($request->user(), $mealId);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'meal_id' => $result['meal_id'],
                    'is_favorited' => $result['is_favorited'],
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Meal not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle favorite',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function check(Request $request, string $mealId, CheckFavoriteAction $action): JsonResponse
    {
        try {
            $result = $action($request->user(), $mealId);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Meal not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check favorite status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function remove(Request $request, string $mealId, RemoveFavoriteAction $action): JsonResponse
    {
        try {
            $result = $action($request->user(), $mealId);

            if ($result['deleted']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Removed from favorites',
                    'data' => [
                        'meal_id' => $result['meal_id'],
                        'is_favorited' => false,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Meal was not in favorites',
                ], 404);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Meal not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove from favorites',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
