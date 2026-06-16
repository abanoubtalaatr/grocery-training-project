<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\OrderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'order' => new OrderResource($this),
            'tracking' => [
                'position' => $this->status_position,
                'status' => $this->status,
                'status_description' => $this->status_description,
                'positions' => $this->getTrackingSteps(),
            ],
        ];
    }

    private function getTrackingSteps(): array
    {
        return [
            [
                'position' => 1,
                'status' => 'pending',
                'label' => 'Order Pending',
                'description' => 'Your order has been placed and is pending confirmation',
                'completed' => in_array($this->status, [
                    'pending',
                    'confirmed',
                    'preparing',
                    'ready',
                    'out_for_delivery',
                    'delivered',
                ]),
                'timestamp' => $this->created_at,
            ],
            [
                'position' => 2,
                'status' => 'confirmed',
                'label' => 'Confirmed',
                'description' => 'Your order has been confirmed',
                'completed' => in_array($this->status, [
                    'confirmed',
                    'preparing',
                    'ready',
                    'out_for_delivery',
                    'delivered',
                ]),
                'timestamp' => $this->confirmed_at,
            ],
            [
                'position' => 3,
                'status' => 'preparing',
                'label' => 'Preparing',
                'description' => 'Your order is being prepared',
                'completed' => in_array($this->status, [
                    'preparing',
                    'ready',
                    'out_for_delivery',
                    'delivered',
                ]),
                'timestamp' => $this->preparing_at,
            ],
            [
                'position' => 4,
                'status' => 'ready',
                'label' => 'Ready',
                'description' => 'Your order is ready for delivery',
                'completed' => in_array($this->status, [
                    'ready',
                    'out_for_delivery',
                    'delivered',
                ]),
                'timestamp' => $this->ready_at,
            ],
            [
                'position' => 5,
                'status' => 'out_for_delivery',
                'label' => 'Out for Delivery',
                'description' => 'Your order is on the way',
                'completed' => in_array($this->status, [
                    'out_for_delivery',
                    'delivered',
                ]),
                'timestamp' => $this->out_for_delivery_at,
            ],
            [
                'position' => 6,
                'status' => 'delivered',
                'label' => 'Delivered',
                'description' => 'Your order has been delivered',
                'completed' => $this->status === 'delivered',
                'timestamp' => $this->delivered_at,
            ],
        ];
    }
}
