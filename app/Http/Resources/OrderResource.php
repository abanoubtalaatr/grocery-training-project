<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'order_number'            => $this->order_number,
            'status'                  => $this->status,
            'status_description'      => $this->status_description,
            'status_position'         => $this->status_position,
            'payment_method'          => $this->payment_method,
            'delivery_type'           => $this->delivery_type,
            'delivery_speed'          => $this->delivery_speed,
            'schedule_delivery'       => $this->schedule_delivery,
            'subtotal'                => $this->subtotal,
            'tax'                     => $this->tax,
            'discount'                => $this->discount,
            'shipping_fee'            => $this->shipping_fee,
            'total'                   => $this->total,
            'notes'                   => $this->notes,
            'placed_at'               => $this->placed_at,
            'processing_at'           => $this->processing_at,
            'shipping_at'             => $this->shipping_at,
            'out_for_delivery_at'     => $this->out_for_delivery_at,
            'delivered_at'            => $this->delivered_at,
            'cancelled_at'            => $this->cancelled_at,
            'estimated_delivery_time' => $this->estimated_delivery_time,
            'user'                    => new UserResource($this->whenLoaded('user')),
            'address'                 => new AddressResource($this->whenLoaded('address')),
            'items'                   => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
        ];
    }
}