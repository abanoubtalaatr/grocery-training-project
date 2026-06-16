<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,

            'payment_method' => $this->payment_method,

            'delivery_type' => $this->delivery_type,

            'status' => $this->status,

            'status_position' => $this->status_position,

            'status_description' => $this->status_description,

            'items' => $this->items->map(function ($item) {

                return [
                    'id' => $item->id,

                    'meal' => [
                        'id' => $item->meal->id,
                        'title' => $item->meal->title,
                        'slug' => $item->meal->slug,
                        'image_url' => $item->meal->image_url,
                    ],

                    'quantity' => $item->quantity,

                    'unit_price' =>
                        (float) $item->unit_price,

                    'discount_amount' =>
                        (float) $item->discount_amount,

                    'subtotal' =>
                        (float) $item->subtotal,
                ];
            }),

            'address' => $this->when(
                $this->address,
                fn () => [
                    'id' => $this->address->id,
                    'label' => $this->address->label,
                    'full_name' => $this->address->full_name,
                    'phone' => $this->address->phone,
                    'full_address' => $this->address->full_address,
                ]
            ),

            'subtotal' => $this->subtotal,

            'tax' => $this->tax,

            'discount' => $this->discount,

            'shipping_fee' => $this->shipping_fee,

            'total' => $this->total,

            'notes' => $this->notes,

            'created_at' => $this->created_at,
        ];
    }
}