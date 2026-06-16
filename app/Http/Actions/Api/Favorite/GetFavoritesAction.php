<?php 

namespace App\Http\Actions\Api\Favorite;

use App\Models\User;

class GetFavoritesAction
{
    public function execute(
        User $user
    ) {
        return $user->favorites()
            ->with([
                'meal.category',
                'meal.subcategory',
            ])
            ->latest()
            ->get();
    }
}