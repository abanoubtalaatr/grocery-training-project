<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'meal' => $this->meal,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'discount_amount' => $this->discount_amount,
            'subtotal' => $this->subtotal,
        ];
    }
}
