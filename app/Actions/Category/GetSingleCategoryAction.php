<?php

namespace App\Actions\Category;

use App\Models\Category;

class GetSingleCategoryAction
{
    public function execute(string $id): Category
    {
        return Category::with(['meals' => function ($query) {
            $query->available()->orderBy('created_at', 'desc');
        }])->findOrFail($id);
    }
}