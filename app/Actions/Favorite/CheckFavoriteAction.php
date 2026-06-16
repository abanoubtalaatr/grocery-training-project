<?php

namespace App\Actions\Favorite;

use App\Models\Meal;
use App\Models\User;

class CheckFavoriteAction
{
    public function execute(User $user, Meal $meal): bool
    {
        return $user->favorites()
            ->where('meal_id', $meal->id)
            ->exists();
    }
}
