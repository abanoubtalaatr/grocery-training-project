<?php

namespace App\Actions\Meal;

use App\Models\User;
use App\Services\FrequencyService;
use App\Traits\FormatsMeal;
use Exception;

class GetFrequencyMealsAction
{
    use FormatsMeal;

    public function __construct(private readonly FrequencyService $frequencyService) {}

    public function __invoke(?User $user, string $frequencyType, ?int $subcategoryId): array
    {
        if ($user === null) {
            throw new Exception('Authentication required to view frequency meals.', 401);
        }

        if (! in_array($frequencyType, FrequencyService::VALID_TYPES, true)) {
            $frequencyType = FrequencyService::FREQUENCY_WEEKLY;
        }

        $meals = $this->frequencyService->getFrequentlyOrderedMeals($user, $frequencyType, 50, $subcategoryId);

        return $meals->map(function ($meal) {
            return $this->formatMealForApi($meal);
        })->values()->toArray();
    }
}
