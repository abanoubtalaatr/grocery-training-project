<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $item = $this->resource;

        return [
            'id' => $item->id,
            'meal' => [
                'id' => $item->meal->id,
                'title' => $item->meal->title,
                'slug' => $item->meal->slug,
                'image_url' => $item->meal->image_url,
                ...$item->meal->getApiPriceAttributes(),
                'rating' => (float) $item->meal->rating,
                'size' => $item->meal->size,
                'brand' => $item->meal->brand,
                'stock_quantity' => $item->meal->stock_quantity,
                'is_available' => $item->meal->is_available,
                'in_stock' => $item->meal->isInStock(),
                'category' => $item->meal->category ? ['id' => $item->meal->category->id, 'name' => $item->meal->category->name] : null,
                'subcategory' => $item->meal->subcategory ? ['id' => $item->meal->subcategory->id, 'name' => $item->meal->subcategory->name] : null,
            ],
            'quantity' => $item->quantity,
            'unit_price' => (float) $item->unit_price,
            'discount_amount' => (float) $item->discount_amount,
            'subtotal' => (float) $item->subtotal,
        ];
    }
}
