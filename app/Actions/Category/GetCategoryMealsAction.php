<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetCategoryMealsAction
{
    public function execute(
        Category $category,
        array $filters = []
    ): LengthAwarePaginator {

        $query = $category->meals()
            ->with('subcategory')
            ->available();

        // Featured filter
        if (array_key_exists('featured', $filters)) {
            (bool) $filters['featured']
                ? $query->featured()
                : $query->where('is_featured', false);
        }

        // Subcategory filter
        if (! empty($filters['subcategory_id'])) {
            $query->where(
                'subcategory_id',
                $filters['subcategory_id']
            );
        }

        // Stock filter
        if (array_key_exists('in_stock', $filters)) {
            (bool) $filters['in_stock']
                ? $query->inStock()
                : $query->outOfStock();
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';

        $sortOrder = strtolower(
            $filters['sort_order'] ?? 'desc'
        ) === 'asc'
            ? 'asc'
            : 'desc';

        if ($sortBy === 'newest') {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }

        $allowedSortFields = [
            'created_at',
            'price',
            'rating',
            'title',
            'sold_count',
        ];

        if (in_array($sortBy, $allowedSortFields)) {

            if ($sortBy === 'price') {

                $query->orderByRaw(
                    'COALESCE(discount_price, price) ' . $sortOrder
                );

            } else {

                $query->orderBy(
                    $sortBy,
                    $sortOrder
                );
            }

        } else {

            $query->latest();
        }

        $perPage = min(
            max((int) ($filters['per_page'] ?? 15), 1),
            50
        );

        return $query->paginate($perPage);
    }
}
