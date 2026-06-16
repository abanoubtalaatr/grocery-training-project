<?php

namespace App\Actions\Favorite;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetFavoritesAction
{
    public function execute(User $user): Collection
    {
        return $user->favorites()
            ->with(['meal.category', 'meal.subcategory'])
            ->latest()
            ->get();
    }
}
