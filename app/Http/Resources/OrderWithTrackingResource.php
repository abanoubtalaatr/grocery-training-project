<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderWithTrackingResource extends JsonResource
{
    public function toArray($request): array
    {
        $order = $this->resource;

        $trackingStage = match ($order->status) {
            'shipping' => 'arriving',
            'out_for_delivery' => 'out_for_delivery',
            'delivered' => 'delivered',
            default => 'processing',
        };

        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_description' => $order->status_description,
            'tracking' => [
                'stage' => $trackingStage,
                'stage_label' => match ($trackingStage) {
                    'arriving' => 'Arriving',
                    'out_for_delivery' => 'Out for delivery',
                    'delivered' => 'Delivered',
                    default => 'Processing',
                },
                'positions' => [
                    ['stage' => 'arriving', 'label' => 'Arriving', 'completed' => in_array($order->status, ['shipping', 'out_for_delivery', 'delivered']), 'timestamp' => $order->shipping_at?->toIso8601String()],
                    ['stage' => 'out_for_delivery', 'label' => 'Out for delivery', 'completed' => in_array($order->status, ['out_for_delivery', 'delivered']), 'timestamp' => $order->out_for_delivery_at?->toIso8601String()],
                    ['stage' => 'delivered', 'label' => 'Delivered', 'completed' => $order->status === 'delivered', 'timestamp' => $order->delivered_at?->toIso8601String()],
                ],
            ],
            'total' => (float) $order->total,
            'placed_at' => $order->placed_at?->toIso8601String(),
            'estimated_delivery_time' => $order->estimated_delivery_time?->toIso8601String(),
            'address' => $order->address ? new AddressResource($order->address) : null,
            'items' => $order->items->map(fn ($item) => [
                'id' => $item->id,
                'meal' => ['id' => $item->meal->id, 'title' => $item->meal->title, 'image_url' => $item->meal->image_url],
                'quantity' => $item->quantity,
                'subtotal' => (float) $item->subtotal,
            ])->values(),
        ];
    }
}
