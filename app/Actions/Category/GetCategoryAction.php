<?php

namespace App\Actions\Category;

use App\Models\Category;

class GetCategoryAction
{
    public function execute(Category $category): Category
    {
        return $category->load([
            'meals' => fn ($query) => $query->available()->latest(),
        ]);
    }
}
