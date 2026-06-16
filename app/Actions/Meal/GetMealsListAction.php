<?php

namespace App\Actions\Meal;

use App\Repositories\MealRepository;
use App\Traits\FormatsMeal;

class GetMealsListAction
{
    use FormatsMeal;

    public function __construct(private readonly MealRepository $mealRepository) {}

    public function moreToExplore()
    {
        return $this->mealRepository->getMoreToExplore();
    }

    public function brands()
    {
        return $this->mealRepository->getBrands();
    }

    public function slider()
    {
        return $this->mealRepository->getSlider()->map(function ($meal) {
            return $this->formatMealForApi($meal);
        });
    }

    public function bestSells()
    {
        return $this->mealRepository->getBestSells();
    }

    public function newProducts()
    {
        return $this->mealRepository->getNewProducts();
    }

    public function hot()
    {
        return $this->mealRepository->getHotMeals()->map(function ($meal) {
            return $this->formatMealForApi($meal);
        });
    }

    public function today()
    {
        return $this->mealRepository->getTodayDeals()->map(function ($meal) {
            return $this->formatMealForApi($meal);
        });
    }
}
