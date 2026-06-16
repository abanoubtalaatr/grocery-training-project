<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SubcategoryDetailsResource extends SubcategoryResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'meals' => MealResource::collection($this->whenLoaded('meals')),
        ]);
    }
}
