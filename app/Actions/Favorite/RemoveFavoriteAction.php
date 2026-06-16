<?php

namespace App\Actions\Favorite;

use App\Models\User;
use App\Repositories\FavoriteRepository;

class RemoveFavoriteAction
{
    public function __construct(private readonly FavoriteRepository $favoriteRepository) {}

    public function __invoke(User $user, string $mealId): array
    {
        $meal = $this->favoriteRepository->findMeal($mealId);
        $deletedCount = $this->favoriteRepository->deleteForUser($user, $meal->id);

        return [
            'meal_id' => $meal->id,
            'deleted' => $deletedCount > 0,
        ];
    }
}
