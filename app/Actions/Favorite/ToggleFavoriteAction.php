<?php

namespace App\Actions\Favorite;

use App\Models\User;
use App\Repositories\FavoriteRepository;
use Illuminate\Support\Facades\DB;

class ToggleFavoriteAction
{
    public function __construct(private readonly FavoriteRepository $favoriteRepository) {}

    public function __invoke(User $user, string $mealId): array
    {
        $meal = $this->favoriteRepository->findMeal($mealId);

        return DB::transaction(function () use ($user, $meal) {
            $favorite = $this->favoriteRepository->findFavoriteForUser($user, $meal->id);

            if ($favorite) {
                $favorite->delete();
                $isFavorited = false;
                $message = 'Removed from favorites';
            } else {
                $this->favoriteRepository->createForUser($user, $meal->id);
                $isFavorited = true;
                $message = 'Added to favorites';
            }

            return [
                'meal_id' => $meal->id,
                'is_favorited' => $isFavorited,
                'message' => $message,
            ];
        });
    }
}
