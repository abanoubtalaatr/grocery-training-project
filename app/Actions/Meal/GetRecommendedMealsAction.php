<?php

namespace App\Actions\Meal;

use App\Repositories\MealRepository;
use App\Traits\FormatsMeal;

class GetRecommendedMealsAction
{
    use FormatsMeal;

    public function __construct(private readonly MealRepository $mealRepository) {}

    public function __invoke(int $limit)
    {
        $recommendations = $this->mealRepository->getRecommendations($limit);

        return $recommendations->map(function ($meal) {
            $data = $this->formatMealForApi($meal);
            $data['recommendation_reason'] = $this->getRecommendationReason($meal);
            return $data;
        })->values();
    }

    private function getRecommendationReason($meal): string
    {
        if ($meal->is_featured && $meal->discount_price) {
            return 'Featured with special offer';
        }

        if ($meal->is_featured) {
            return 'Featured meal';
        }

        if ($meal->discount_price) {
            return 'Special offer';
        }

        return 'Popular choice';
    }
}
