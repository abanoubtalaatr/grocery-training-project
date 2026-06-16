<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'order' => $this->order,
            'is_active' => $this->when(isset($this->is_active), $this->is_active),
            'category' => $this->category
                ? CategoryResource::make($this->category)
                : null,
            'meals_count' => $this->meals_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->when(isset($this->updated_at), $this->updated_at),
        ];
    }
}
