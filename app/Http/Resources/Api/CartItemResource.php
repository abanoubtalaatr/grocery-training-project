<?php

namespace App\Http\Resources\Api;

// use Illuminate\Http\Request;
use App\Http\Resources\Api\MealSummaryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'meal' => new MealSummaryResource(
                $this->meal
            ),

            'quantity' => $this->quantity,

            'unit_price' => (float) $this->unit_price,

            'discount_amount' => (float) $this->discount_amount,

            'subtotal' => (float) $this->subtotal,
        ];
    }
}