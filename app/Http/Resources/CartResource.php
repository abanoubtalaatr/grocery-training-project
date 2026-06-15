<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    protected ?float $shippingFee = null;
    protected ?float $totalWithShipping = null;

    /**
     * Set dynamic shipping calculation details on the resource.
     */
    public function withShipping(?float $shippingFee, ?float $totalWithShipping): self
    {
        $this->shippingFee = $shippingFee;
        $this->totalWithShipping = $totalWithShipping;
        return $this;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->isEmpty() ? 'empty' : 'not empty',
            'items' => CartItemResource::collection($this->whenLoaded('items') ?? $this->items),
            'item_count' => $this->item_count,
            'subtotal' => (float) $this->subtotal,
            'tax' => (float) $this->tax,
            'discount' => (float) $this->discount,
            'total' => (float) $this->total,
            'is_empty' => $this->isEmpty(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'shipping_fee' => $this->when($this->shippingFee !== null, fn() => (float) $this->shippingFee),
            'total_with_shipping' => $this->when($this->totalWithShipping !== null, fn() => (float) $this->totalWithShipping),
        ];
    }
}
