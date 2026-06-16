<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Meal;

class FavoriteRepository
{
    public function getForUser(User $user)
    {
        return $user->favorites()->with(['meal.category', 'meal.subcategory'])->latest()->get();
    }

    public function findMeal(string $mealId): Meal
    {
        return Meal::findOrFail($mealId);
    }

    public function findFavoriteForUser(User $user, string $mealId)
    {
        return $user->favorites()->where('meal_id', $mealId)->first();
    }

    public function createForUser(User $user, string $mealId)
    {
        return $user->favorites()->create(['meal_id' => $mealId]);
    }

    public function deleteForUser(User $user, string $mealId): int
    {
        return $user->favorites()->where('meal_id', $mealId)->delete();
    }

    public function existsForUser(User $user, string $mealId): bool
    {
        return $user->favorites()->where('meal_id', $mealId)->exists();
    }
}
