<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryMealsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $subcategory = $this->resource['subcategory'];
        $paginator = $this->resource['paginator'];

        return [
            'subcategory' => [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'slug' => $subcategory->slug,
            ],
            'meals' => MealResource::collection($paginator->getCollection()),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }
}
