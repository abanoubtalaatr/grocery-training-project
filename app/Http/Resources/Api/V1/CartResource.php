<?php

namespace App\Http\Resources;

use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    protected ?float $shippingFee;
    protected ?float $totalWithShipping;

    public function __construct($resource, ?float $shippingFee = null, ?float $totalWithShipping = null)
    {
        parent::__construct($resource);
        $this->shippingFee = $shippingFee;
        $this->totalWithShipping = $totalWithShipping;
    }

    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'status'     => $this->isEmpty() ? 'empty' : 'not empty',
            'item_count' => $this->item_count,
            'subtotal'   => (float) $this->subtotal,
            'tax'        => (float) $this->tax,
            'discount'   => (float) $this->discount,
            'total'      => (float) $this->total,
            'is_empty'   => $this->isEmpty(),
            'items'      => CartItemResource::collection($this->whenLoaded('items')),
            $this->mergeWhen($this->shippingFee !== null, [
                'shipping_fee'        => $this->shippingFee,
                'total_with_shipping' => $this->totalWithShipping,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}