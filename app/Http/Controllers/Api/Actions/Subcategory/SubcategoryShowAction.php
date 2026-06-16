<?php

namespace App\Http\Controllers\Api\Actions\Subcategory;

use App\Models\Subcategory;

class SubcategoryShowAction
{
    public function handle(string $id): Subcategory
    {
        return Subcategory::with(['category', 'meals' => function ($query) {
            $query->available()->limit(10);
        }])->findOrFail($id);
    }
}
