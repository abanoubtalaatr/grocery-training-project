<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'offer_title' => $this->offer_title,
            ...($this->getApiPriceAttributes() ?? []),
            'has_offer' => $this->hasOffer(),
            'rating' => (float) $this->rating,
            'rating_count' => (int) $this->rating_count,
            'size' => $this->size,
            'brand' => $this->brand,
            'includes' => $this->when(isset($this->includes), fn() => $this->includes),
            'how_to_use' => $this->when(isset($this->how_to_use), fn() => $this->how_to_use),
            'stock_quantity' => $this->stock_quantity,
            'in_stock' => $this->isInStock(),
            'is_available' => $this->is_available,
            'is_featured' => $this->is_featured,
            'sold_count' => $this->when(isset($this->sold_count), fn() => (int) $this->sold_count),
            'expiry_date' => $this->when(isset($this->expiry_date), fn() => $this->expiry_date),
            'days_until_expiry' => $this->when($this->expiry_date !== null, fn() => $this->daysUntilExpiry()),
            'is_expired' => $this->when($this->expiry_date !== null, fn() => $this->isExpired()),
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'subcategory' => $this->subcategory ? [
                'id' => $this->subcategory->id,
                'name' => $this->subcategory->name,
                'slug' => $this->subcategory->slug,
            ] : null,
            'features' => $this->features,
            'is_favorited' => $this->when(isset($this->is_favorited), fn() => (bool) $this->is_favorited),
            'recommendation_reason' => $this->when(isset($this->recommendation_reason), fn() => $this->recommendation_reason),
            'order_count' => $this->when(isset($this->order_count), fn() => (int) $this->order_count),
            'reviews' => $this->whenLoaded('reviews', fn() => $this->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => $review->user ? [
                        'id' => $review->user->id,
                        'name' => $review->user->full_name ?? $review->user->username ?? 'User',
                    ] : null,
                    'rating' => (int) $review->rating,
                    'comment' => $review->comment,
                    'images' => $review->images ?? [],
                    'created_at' => $review->created_at?->toIso8601String(),
                ];
            })->values()),
            'available_date' => $this->available_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
