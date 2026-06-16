<?php

namespace App\Actions\Favorite;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ToggleFavoriteAction
{
    public function execute(User $user, Meal $meal): bool
    {
        return DB::transaction(function () use ($user, $meal) {
            $favorite = $user->favorites()
                ->where('meal_id', $meal->id)
                ->first();

            if ($favorite) {
                $favorite->delete();

                return false;
            }

            $user->favorites()->create([
                'meal_id' => $meal->id,
            ]);

            return true;
        });
    }
}
