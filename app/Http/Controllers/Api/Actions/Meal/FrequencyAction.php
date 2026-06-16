<?php

namespace App\Http\Controllers\Api\Actions\Meal;

use App\Services\FrequencyService;

class FrequencyAction
{
    public function handle($user, string $frequencyType = null, int $limit = 50, $subcategoryId = null)
    {
        $frequencyType = $frequencyType ?? FrequencyService::FREQUENCY_WEEKLY;
        $service = app(FrequencyService::class);

        return $service->getFrequentlyOrderedMeals($user, $frequencyType, $limit, $subcategoryId);
    }
}
