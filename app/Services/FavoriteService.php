<?php

namespace App\Services;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FavoriteService
{
    public function getFavorites(User $user): Collection
    {
        return $user->favorites()
            ->with(['meal.category', 'meal.subcategory'])
            ->latest()
            ->get();
    }

    public function toggleFavorite(User $user, int $mealId): array
    {
        $meal = Meal::findOrFail($mealId);

        return DB::transaction(function () use ($user, $meal) {
            $favorite = $user->favorites()->where('meal_id', $meal->id)->first();

            if ($favorite) {
                $favorite->delete();
                return ['is_favorited' => false, 'message' => 'Removed from favorites'];
            }

            $user->favorites()->create(['meal_id' => $meal->id]);
            return ['is_favorited' => true, 'message' => 'Added to favorites'];
        });
    }

    public function isFavorited(User $user, int $mealId): bool
    {
        return $user->favorites()->where('meal_id', $mealId)->exists();
    }
}
