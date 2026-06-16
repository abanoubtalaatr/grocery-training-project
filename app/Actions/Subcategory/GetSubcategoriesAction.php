<?php

namespace App\Actions\Subcategory;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Collection;

class GetSubcategoriesAction
{
    public function execute(array $filters = []): Collection
    {
        return Subcategory::query()
            ->with('category')
            ->withCount(['meals' => fn ($query) => $query->available()])
            ->active()
            ->when(
                ! empty($filters['category_id']),
                fn ($query) => $query->where('category_id', $filters['category_id'])
            )
            ->inRandomOrder()
            ->get();
    }
}
