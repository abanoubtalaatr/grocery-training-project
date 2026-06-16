<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
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
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'is_available' => $this->is_available,
            'sort_order' => $this->sort_order,
        ];
    }
}
