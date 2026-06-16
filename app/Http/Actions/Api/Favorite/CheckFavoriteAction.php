<?php

namespace App\Http\Actions\Api\Favorite;


use App\Models\Meal;
use App\Models\User;


class CheckFavoriteAction
{
    public function execute(
        User $user,
        string $mealId
    ): array {

        $meal = Meal::findOrFail(
            $mealId
        );

        return [
            'meal_id'
                => $meal->id,

            'is_favorited'
                => $user->favorites()
                    ->where(
                        'meal_id',
                        $meal->id
                    )
                    ->exists(),
        ];
    }
}