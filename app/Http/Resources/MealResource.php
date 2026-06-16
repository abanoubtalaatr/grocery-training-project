<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'offer_title' => $this->offer_title,

            ...$this->getApiPriceAttributes(),

            'rating' => (float) $this->rating,
            'rating_count' => (int) $this->rating_count,
            'has_offer' => $this->hasOffer(),
            'size' => $this->size,
            'brand' => $this->brand,
            'stock_quantity' => $this->stock_quantity,
            'in_stock' => $this->isInStock(),
            'is_available' => $this->is_available,
            'is_featured' => $this->is_featured,
            'features' => $this->features,
        ];
    }
}
