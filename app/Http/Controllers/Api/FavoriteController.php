<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Services\FavoriteService;
use App\Services\MealService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $favoriteService;
    protected $mealService;

    public function __construct(FavoriteService $favoriteService, MealService $mealService)
    {
        $this->favoriteService = $favoriteService;
        $this->mealService = $mealService;
    }

    /**
     * Get all user's favorite meals
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $favorites = $this->favoriteService->getFavorites($user);

        $formatted = $favorites->map(function ($favorite) {
            return $this->mealService->formatMeal($favorite->meal, [$favorite->meal_id]);
        });

        return self::collectionResponse('Favorites retrieved successfully', $formatted, [
            'total_count' => $formatted->count(),
        ]);
    }

    /**
     * Toggle favorite status (using store as it's an action on a collection/resource)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['meal_id' => 'required|exists:meals,id']);

        $result = $this->favoriteService->toggleFavorite($request->user(), $request->meal_id);

        return self::successResponse($result['message'], [
            'meal_id' => (int) $request->meal_id,
            'is_favorited' => $result['is_favorited'],
        ]);
    }

    /**
     * Check if a meal is favorited
     */
    public function show(Request $request, Meal $meal): JsonResponse
    {
        $isFavorited = $this->favoriteService->isFavorited($request->user(), $meal->id);

        return self::successResponse('Favorite status retrieved', [
            'meal_id' => $meal->id,
            'is_favorited' => $isFavorited,
        ]);
    }

    /**
     * Remove meal from favorites
     */
    public function destroy(Request $request, Meal $meal): JsonResponse
    {
        $deleted = $request->user()->favorites()->where('meal_id', $meal->id)->delete();

        if ($deleted) {
            return self::successResponse('Removed from favorites', [
                'meal_id' => $meal->id,
                'is_favorited' => false,
            ]);
        }

        return self::errorResponse('Meal was not in favorites', null, 404);
    }
}
