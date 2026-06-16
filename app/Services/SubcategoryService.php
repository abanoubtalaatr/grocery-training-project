<?php

namespace App\Services;

use App\Models\Subcategory;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SubcategoryService
{
    public function getSubcategories(array $filters): Collection
    {
        $query = Subcategory::with('category')->active();

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->inRandomOrder()->get();
    }

    public function formatSubcategory(Subcategory $subcategory): array
    {
        return [
            'id' => $subcategory->id,
            'name' => $subcategory->name,
            'slug' => $subcategory->slug,
            'description' => $subcategory->description,
            'image_url' => $subcategory->image_url,
            'order' => $subcategory->order,
            'category' => $subcategory->category ? [
                'id' => $subcategory->category->id,
                'name' => $subcategory->category->name,
            ] : null,
            'meals_count' => $subcategory->meals()->available()->count(),
            'created_at' => $subcategory->created_at,
        ];
    }

    public function getSubcategoryMeals(Subcategory $subcategory, array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $subcategory->meals()->with('category')->available();

        if (isset($filters['featured'])) {
            filter_var($filters['featured'], FILTER_VALIDATE_BOOLEAN)
                ? $query->featured()
                : $query->where('is_featured', false);
        }

        if (isset($filters['in_stock'])) {
            filter_var($filters['in_stock'], FILTER_VALIDATE_BOOLEAN)
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
        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'price') {
                $query->orderByRaw('COALESCE(discount_price, price) ' . $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }
}
