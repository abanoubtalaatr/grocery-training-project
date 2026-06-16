<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\MealResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'quantity'        => $this->quantity,
            'unit_price'      => (float) $this->unit_price,
            'discount_amount' => (float) $this->discount_amount,
            'subtotal'        => (float) $this->subtotal,
            
            
            'meal'            => new MealResource($this->whenLoaded('meal')),
        ];
    }
}