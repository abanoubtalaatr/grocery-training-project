<?php

namespace App\Repositories;

use App\Models\Meal;

class MealRepository
{
    public function getMoreToExplore()
    {
        return Meal::with('category')->available()->orderBy('created_at', 'desc')->get();
    }

    public function getBrands()
    {
        return Meal::distinct()->pluck('brand');
    }

    public function getSlider()
    {
        return Meal::with('category')->available()->orderBy('created_at', 'desc')->get();
    }

    public function getBestSells()
    {
        return Meal::with('category')->available()->take(10)->get();
    }

    public function getNewProducts()
    {
        return Meal::with('category')->available()->orderBy('created_at', 'desc')->get();
    }

    public function getHotMeals()
    {
        return Meal::with('category')->available()->hot()->orderBy('created_at', 'desc')->get();
    }

    public function getTodayDeals()
    {
        return Meal::with('category')->available()->withActiveDiscount()->orderBy('created_at', 'desc')->get();
    }

    public function getFiltered(array $filters)
    {
        $query = Meal::with(['category', 'subcategory'])->available();
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (isset($filters['subcategory_id'])) {
            $query->where('subcategory_id', $filters['subcategory_id']);
        }
        if (isset($filters['featured'])) {
            $filters['featured'] ? $query->featured() : $query->where('is_featured', false);
        }
        if (isset($filters['in_stock'])) {
            $filters['in_stock'] ? $query->inStock() : $query->outOfStock();
        }
        if (isset($filters['min_price'])) {
            $query->whereRaw('COALESCE(discount_price, price) >= ?', [$filters['min_price']]);
        }
        if (isset($filters['max_price'])) {
            $query->whereRaw('COALESCE(discount_price, price) <= ?', [$filters['max_price']]);
        }
        if (isset($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }
        if (isset($filters['brand'])) {
            $query->where('brand', $filters['brand']);
        }
        
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        if ($sortBy === 'price') {
            $query->orderByRaw('COALESCE(discount_price, price) '.$sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        return $query->get();
    }

    public function getRecommendations(int $limit)
    {
        $featuredMeals = Meal::with('category')
            ->available()
            ->featured()
            ->whereNotNull('discount_price')
            ->inRandomOrder()
            ->limit(ceil($limit / 2))
            ->get();

        $randomMeals = Meal::with('category')
            ->available()
            ->whereNotIn('id', $featuredMeals->pluck('id'))
            ->inRandomOrder()
            ->limit($limit - $featuredMeals->count())
            ->get();

        return $featuredMeals->merge($randomMeals)->shuffle()->take($limit);
    }

    public function findByIdWithDetails(string $id)
    {
        return Meal::with([
            'category',
            'subcategory',
            'reviews' => fn ($q) => $q->approved()->with('user:id,username,firstname,lastname')->orderBy('created_at', 'desc'),
        ])->findOrFail($id);
    }
}
