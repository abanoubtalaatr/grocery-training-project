<?php

namespace App\Http\Resources\Api;

// use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request): array
{
    return [
        'id' => $this->id,

        'items' => CartItemResource::collection(
            $this->items
        ),

        'item_count' => $this->item_count,

        'subtotal' => (float) $this->subtotal,

        'tax' => (float) $this->tax,

        'discount' => (float) $this->discount,

        'total' => (float) $this->total,

        'shipping_fee' => $this->shipping_fee,

        'total_with_shipping' =>
            $this->total_with_shipping,

        'is_empty' => $this->isEmpty(),
    ];
}
}