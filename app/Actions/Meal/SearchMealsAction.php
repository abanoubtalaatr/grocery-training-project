<?php

namespace App\Actions\Meal;

use App\Models\User;
use App\Repositories\MealRepository;
use App\Traits\FormatsMeal;

class SearchMealsAction
{
    use FormatsMeal;

    public function __construct(private readonly MealRepository $mealRepository) {}

    public function __invoke(array $filters, ?User $user)
    {
        $meals = $this->mealRepository->getFiltered($filters);
        
        $favoriteMealIds = [];
        if ($user) {
            $favoriteMealIds = $user->favorites()->pluck('meal_id')->toArray();
        }

        return $meals->map(function ($meal) use ($favoriteMealIds) {
            return $this->formatMealForApi($meal, $favoriteMealIds);
        });
    }
}
