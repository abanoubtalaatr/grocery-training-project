<?php

namespace App\Http\Actions\Api\Favorite;

use App\Models\Meal;
use App\Models\User;

class RemoveFavoriteAction
{
    public function execute(
        User $user,
        string $mealId
    ): array {

        $meal = Meal::findOrFail(
            $mealId
        );

        $deleted = $user->favorites()
            ->where(
                'meal_id',
                $meal->id
            )
            ->delete();

        if (! $deleted) {
            abort(
                404,
                'Meal was not in favorites'
            );
        }

        return [
            'meal_id'
                => $meal->id,

            'is_favorited'
                => false,
        ];
    }
}