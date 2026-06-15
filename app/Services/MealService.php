<?php

namespace App\Services;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MealService
{
    public function getMeals(array $filters, ?User $user = null): Collection
    {
        $query = Meal::with(['category', 'subcategory'])->available();

        if (isset($filters['search'])) {
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
            filter_var($filters['featured'], FILTER_VALIDATE_BOOLEAN) 
                ? $query->featured() 
                : $query->where('is_featured', false);
        }

        if (isset($filters['in_stock'])) {
            filter_var($filters['in_stock'], FILTER_VALIDATE_BOOLEAN) 
                ? $query->inStock() 
                : $query->outOfStock();
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
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        if ($sortBy === 'newest') {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }

        $allowedSortFields = ['created_at', 'price', 'rating', 'title', 'sold_count'];
        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'price') {
                $query->orderByRaw('COALESCE(discount_price, price) '.$sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    public function formatMeal(Meal $meal, array $favoriteMealIds = []): array
    {
        return [
            'id' => $meal->id,
            'title' => $meal->title,
            'slug' => $meal->slug,
            'description' => $meal->description,
            'image_url' => $meal->image_url,
            'offer_title' => $meal->offer_title,
            ...$meal->getApiPriceAttributes(),
            'has_offer' => $meal->hasOffer(),
            'rating' => (float) $meal->rating,
            'rating_count' => (int) $meal->rating_count,
            'size' => $meal->size,
            'brand' => $meal->brand,
            'stock_quantity' => $meal->stock_quantity,
            'in_stock' => $meal->isInStock(),
            'is_featured' => $meal->is_featured,
            'sold_count' => $meal->sold_count,
            'category' => $meal->category ? [
                'id' => $meal->category->id,
                'name' => $meal->category->name,
            ] : null,
            'subcategory' => $meal->subcategory ? [
                'id' => $meal->subcategory->id,
                'name' => $meal->subcategory->name,
            ] : null,
            'features' => $meal->features,
            'is_favorited' => in_array($meal->id, $favoriteMealIds),
            'created_at' => $meal->created_at,
        ];
    }

    public function getRecommendedMeals(int $limit = 10): Collection
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
}
