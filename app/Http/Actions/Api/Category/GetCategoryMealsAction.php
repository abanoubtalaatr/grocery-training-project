<?php

namespace App\Http\Actions\Api\Category;

use App\Models\Category;


class GetCategoryMealsAction
{
    public function execute(
        Category $category,
        array $filters
    ) {
        $query = $category->meals()
            ->with('subcategory')
            ->available();

        if (isset($filters['featured'])) {

            $filters['featured']
                ? $query->featured()
                : $query->where(
                    'is_featured',
                    false
                );
        }

        if (
            isset($filters['subcategory_id'])
        ) {
            $query->where(
                'subcategory_id',
                $filters['subcategory_id']
            );
        }

        if (isset($filters['in_stock'])) {

            $filters['in_stock']
                ? $query->inStock()
                : $query->outOfStock();
        }

        $this->applySorting(
            $query,
            $filters
        );

        return $query->paginate(
            $filters['per_page'] ?? 15
        );
    }

    private function applySorting(
        $query,
        array $filters
    ): void {

        $sortBy =
            $filters['sort_by']
            ?? 'created_at';

        $sortOrder =
            $filters['sort_order']
            ?? 'desc';

        if ($sortBy === 'newest') {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }

        if ($sortBy === 'price') {

            $query->orderByRaw(
                'COALESCE(discount_price, price) '
                .$sortOrder
            );

            return;
        }

        $query->orderBy(
            $sortBy,
            $sortOrder
        );
    }
}