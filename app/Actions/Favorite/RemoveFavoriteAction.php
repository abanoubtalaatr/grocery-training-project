<?php

namespace App\Actions\Favorite;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class RemoveFavoriteAction
{
    public function execute(User $user, Meal $meal): void
    {
        $deleted = $user->favorites()
            ->where('meal_id', $meal->id)
            ->delete();

        if (! $deleted) {
            throw ValidationException::withMessages([
                'meal_id' => ['Meal was not in favorites.'],
            ]);
        }
    }
}
