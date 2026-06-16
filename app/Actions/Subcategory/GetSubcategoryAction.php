<?php

namespace App\Actions\Subcategory;

use App\Models\Subcategory;

class GetSubcategoryAction
{
    public function execute(Subcategory $subcategory): Subcategory
    {
        return $subcategory->load([
            'category',
            'meals' => fn ($query) => $query->available()->limit(10),
        ])->loadCount([
            'meals' => fn ($query) => $query->available(),
        ]);
    }
}
