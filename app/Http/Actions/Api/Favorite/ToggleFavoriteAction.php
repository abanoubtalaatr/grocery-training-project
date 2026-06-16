<?php

namespace App\Http\Actions\Api\Favorite;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ToggleFavoriteAction
{
    public function execute(
        User $user,
        string $mealId
    ): array {

        return DB::transaction(
            function () use (
                $user,
                $mealId
            ) {

                $meal = Meal::findOrFail(
                    $mealId
                );

                $favorite = $user->favorites()
                    ->where(
                        'meal_id',
                        $meal->id
                    )
                    ->first();

                if ($favorite) {

                    $favorite->delete();

                    return [
                        'message'
                            => 'Removed from favorites',

                        'meal_id'
                            => $meal->id,

                        'is_favorited'
                            => false,
                    ];
                }

                $user->favorites()->create([
                    'meal_id'
                        => $meal->id,
                ]);

                return [
                    'message'
                        => 'Added to favorites',

                    'meal_id'
                        => $meal->id,

                    'is_favorited'
                        => true,
                ];
            }
        );
    }
}