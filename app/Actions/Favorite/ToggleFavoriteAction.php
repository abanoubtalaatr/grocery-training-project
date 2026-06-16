<?php

namespace App\Actions\Favorite;

use App\Models\{User, Meal};

class ToggleFavoriteAction
{
    public function toggle(User $user, Meal $meal): array
    {
        $favorite = $user->favorites()->where('meal_id', $meal->id)->first();
        
        if ($favorite) {
            $favorite->delete();
            return ['status' => false, 'message' => 'Removed from favorites'];
        }

        $user->favorites()->create(['meal_id' => $meal->id]);
        return ['status' => true, 'message' => 'Added to favorites'];
    }
}