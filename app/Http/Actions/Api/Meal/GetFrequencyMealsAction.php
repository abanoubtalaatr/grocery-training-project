<?php

namespace App\Http\Actions\Api\Meal;

use App\Models\User;
use App\Services\FrequencyService;

class GetFrequencyMealsAction
{
    public function __construct(
        private readonly FrequencyService $frequencyService
    ) {}

    public function execute(
        User $user,
        string $frequencyType,
        ?int $subcategoryId
    ) {
        return $this->frequencyService
            ->getFrequentlyOrderedMeals(
                $user,
                $frequencyType,
                50,
                $subcategoryId
            );
    }
}