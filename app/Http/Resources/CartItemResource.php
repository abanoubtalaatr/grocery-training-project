<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'meal' => MealResource::make($this->meal),

            'quantity' => $this->quantity,

            'unit_price' => (float) $this->unit_price,

            'discount_amount' => (float) $this->discount_amount,

            'subtotal' => (float) $this->subtotal,
        ];
    }
}