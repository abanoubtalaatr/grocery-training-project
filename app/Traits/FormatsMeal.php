<?php

namespace App\Traits;

use App\Models\Meal;

trait FormatsMeal
{
    protected function formatMealForApi(Meal $meal, ?array $favoriteMealIds = null): array
    {
        $data = [
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
            'stock_quantity' => (int) $meal->stock_quantity,
            'in_stock' => $meal->isInStock(),
            'is_available' => $meal->is_available,
            'is_featured' => $meal->is_featured,
            'sold_count' => $meal->sold_count,
            'category' => $meal->category ? [
                'id' => $meal->category->id,
                'name' => $meal->category->name,
                'slug' => $meal->category->slug,
            ] : null,
            'subcategory' => $meal->subcategory ? [
                'id' => $meal->subcategory->id,
                'name' => $meal->subcategory->name,
                'slug' => $meal->subcategory->slug,
            ] : null,
            'features' => $meal->features,
            'available_date' => $meal->available_date,
            'created_at' => $meal->created_at,
        ];

        if ($favoriteMealIds !== null) {
            $data['is_favorited'] = in_array($meal->id, $favoriteMealIds);
        }

        // Additional fields for recommendations or frequency
        if ($meal->hasAttribute('order_count')) {
            $data['order_count'] = (int) $meal->getAttribute('order_count');
        }

        return $data;
    }
}
