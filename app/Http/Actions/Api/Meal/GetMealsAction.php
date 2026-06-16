<?php

namespace App\Http\Actions\Api\Meal;

use App\Models\Meal;

class GetMealsAction
{
    public function execute(
        array $filters,
        ?array $favoriteMealIds = []
    ) {
        $query = Meal::with([
            'category',
            'subcategory',
        ])->available();

        // move all filters here

        return $query->get();
    }
}