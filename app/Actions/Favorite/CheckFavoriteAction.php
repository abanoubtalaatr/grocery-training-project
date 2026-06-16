<?php

namespace App\Actions\Favorite;

use App\Models\User;
use App\Repositories\FavoriteRepository;

class CheckFavoriteAction
{
    public function __construct(private readonly FavoriteRepository $favoriteRepository) {}

    public function __invoke(User $user, string $mealId): array
    {
        $meal = $this->favoriteRepository->findMeal($mealId);
        $isFavorited = $this->favoriteRepository->existsForUser($user, $meal->id);

        return [
            'meal_id' => $meal->id,
            'is_favorited' => $isFavorited,
        ];
    }
}
