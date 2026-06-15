<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $favorite = $this->resource;
        $meal = $favorite->meal;

        return [
            'id' => $meal->id,
            'title' => $meal->title,
            'slug' => $meal->slug,
            'image_url' => $meal->image_url,
            ...$meal->getApiPriceAttributes(),
            'has_offer' => $meal->hasOffer(),
            'category' => $meal->category ? ['id' => $meal->category->id, 'name' => $meal->category->name] : null,
            'is_favorited' => true,
            'favorited_at' => $favorite->created_at?->toIso8601String(),
        ];
    }
}
