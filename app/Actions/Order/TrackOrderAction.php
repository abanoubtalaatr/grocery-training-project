<?php

namespace App\Actions\Order;

use App\Models\User;
use App\Repositories\OrderRepository;
use App\Traits\FormatsOrder;

class TrackOrderAction
{
    use FormatsOrder;

    public function __construct(private readonly OrderRepository $orderRepository) {}

    public function __invoke(User $user): array
    {
        $order = $this->orderRepository->findActiveForUser($user);

        if (!$order) {
            return [
                'status' => 404,
                'message' => 'No active order found',
            ];
        }

        if ($order->status === 'awaiting_payment') {
            return [
                'status' => 200,
                'message' => 'Order is waiting for payment. Complete checkout to continue.',
                'data' => [
                    'order' => $this->formatOrder($order),
                    'awaiting_payment' => true,
                    'tracking' => null,
                ],
            ];
        }

        return [
            'status' => 200,
            'message' => 'Order tracking retrieved successfully',
            'data' => [
                'order' => $this->formatOrder($order),
                'tracking' => [
                    'position' => $order->status_position,
                    'status' => $order->status,
                    'status_description' => $order->status_description,
                    'positions' => [
                        [
                            'position' => 1,
                            'status' => 'placed',
                            'label' => 'Order Placed',
                            'description' => 'Your order has been placed',
                            'completed' => in_array($order->status, ['placed', 'processing', 'shipping', 'out_for_delivery', 'delivered']),
                            'timestamp' => $order->placed_at,
                        ],
                        [
                            'position' => 2,
                            'status' => 'processing',
                            'label' => 'Processing',
                            'description' => 'Your order is being processed',
                            'completed' => in_array($order->status, ['processing', 'shipping', 'out_for_delivery', 'delivered']),
                            'timestamp' => $order->processing_at,
                        ],
                        [
                            'position' => 3,
                            'status' => 'shipping',
                            'label' => 'Shipping',
                            'description' => 'Your order is being shipped',
                            'completed' => in_array($order->status, ['shipping', 'out_for_delivery', 'delivered']),
                            'timestamp' => $order->shipping_at,
                        ],
                        [
                            'position' => 4,
                            'status' => 'out_for_delivery',
                            'label' => 'Out for Delivery',
                            'description' => 'Your order is on the way',
                            'completed' => in_array($order->status, ['out_for_delivery', 'delivered']),
                            'timestamp' => $order->out_for_delivery_at,
                        ],
                        [
                            'position' => 5,
                            'status' => 'delivered',
                            'label' => 'Delivered',
                            'description' => 'Your order has been delivered',
                            'completed' => $order->status === 'delivered',
                            'timestamp' => $order->delivered_at,
                        ],
                    ],
                ],
            ],
        ];
    }
}
