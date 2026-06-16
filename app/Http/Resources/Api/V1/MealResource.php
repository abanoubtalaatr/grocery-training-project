<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'description'       => $this->description,
            'image_url'         => $this->image_url,
            'offer_title'       => $this->offer_title,
            $this->merge($this->getApiPriceAttributes()),
            'has_offer'         => $this->hasOffer(),
            'rating'            => (float) $this->rating,
            'rating_count'      => (int) $this->rating_count,
            'size'              => $this->size,
            'brand'             => $this->brand,
            'stock_quantity'    => $this->stock_quantity,
            'in_stock'          => $this->isInStock(),
            'is_featured'       => $this->is_featured,
            'expiry_date'       => $this->expiry_date,
            'days_until_expiry' => $this->daysUntilExpiry(),
            'is_expired'        => $this->isExpired(),
            'features'          => $this->features,
            'subcategory'       => $this->subcategory ? [
                'id'   => $this->subcategory->id,
                'name' => $this->subcategory->name,
                'slug' => $this->subcategory->slug,
            ] : null,
        ];
    }
}