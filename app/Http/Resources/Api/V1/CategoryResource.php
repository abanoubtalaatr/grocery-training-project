<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\MealResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('meals')) {
            $data['meals'] = MealResource::collection($this->meals);
        }

        return $data;
    }
}
