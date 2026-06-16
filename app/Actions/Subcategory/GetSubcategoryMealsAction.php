<?php

namespace App\Actions\Subcategory;

use App\Models\Subcategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetSubcategoryMealsAction
{
    public function execute(Subcategory $subcategory, array $filters = []): LengthAwarePaginator
    {
        $query = $subcategory->meals()
            ->with('category')
            ->available();

        if (array_key_exists('featured', $filters)) {
            (bool) $filters['featured']
                ? $query->featured()
                : $query->where('is_featured', false);
        }

        if (array_key_exists('in_stock', $filters)) {
            (bool) $filters['in_stock']
                ? $query->inStock()
                : $query->outOfStock();
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        if ($sortBy === 'newest') {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }

        $allowedSortFields = ['created_at', 'price', 'rating', 'title', 'sold_count'];

        if (in_array($sortBy, $allowedSortFields, true)) {
            $sortBy === 'price'
                ? $query->orderByRaw('COALESCE(discount_price, price) ' . $sortOrder)
                : $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $perPage = min(max((int) ($filters['per_page'] ?? 15), 1), 50);

        return $query->paginate($perPage)->withQueryString();
    }
}
