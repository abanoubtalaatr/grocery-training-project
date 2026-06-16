<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'status'     => $this->status,
            'subtotal'   => $this->subtotal,
            'tax'        => $this->tax,
            'discount'   => $this->discount,
            'total'      => $this->total,
            'item_count' => $this->item_count,
            'user'       => new UserResource($this->whenLoaded('user')),
            'items'      => CartItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}