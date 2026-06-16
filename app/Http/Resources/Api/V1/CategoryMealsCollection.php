<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryMealsCollection extends ResourceCollection
{
    protected $category;

    public function __construct($resource, $category)
    {
        parent::__construct($resource);
        $this->category = $category;
    }

    public function toArray(Request $request): array
    {
        $response = [
            'category' => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ],
            'meals' => MealResource::collection($this->collection),
            'pagination' => [
                'current_page' => $this->resource->currentPage(),
                'last_page'    => $this->resource->lastPage(),
                'per_page'     => $this->resource->perPage(),
                'total'        => $this->resource->total(),
                'from'         => $this->resource->firstItem(),
                'to'           => $this->resource->lastItem(),
            ],
        ];

        if ($this->resource->total() === 0) {
            $response['empty_message'] = 'No products match the applied filters. Try adjusting your filters.';
        }

        return $response;
    }
}