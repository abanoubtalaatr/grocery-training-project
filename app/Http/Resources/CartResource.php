<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'status' => $this->isEmpty()
                ? 'empty'
                : 'not empty',

            'items' => CartItemResource::collection(
                $this->items
            ),

            'item_count' => $this->item_count,

            'subtotal' => (float) $this->subtotal,

            'tax' => (float) $this->tax,

            'discount' => (float) $this->discount,

            'total' => (float) $this->total,

            'shipping_fee' => $this->when(
                $this->getAttribute('shipping_fee') !== null,
                fn () => (float) $this->getAttribute('shipping_fee')
            ),

            'total_with_shipping' => $this->when(
                $this->getAttribute('total_with_shipping') !== null,
                fn () => (float) $this->getAttribute('total_with_shipping')
            ),

            'is_empty' => $this->isEmpty(),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}
