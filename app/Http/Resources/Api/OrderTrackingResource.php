<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order' => new OrderResource($this),

            'tracking' => [
                'position' => $this->status_position,

                'status' => $this->status,

                'status_description' =>
                    $this->status_description,

                'positions' => [
                    [
                        'position' => 1,
                        'status' => 'placed',
                        'completed' => in_array(
                            $this->status,
                            [
                                'placed',
                                'processing',
                                'shipping',
                                'out_for_delivery',
                                'delivered',
                            ]
                        ),
                        'timestamp' => $this->placed_at,
                    ],

                    [
                        'position' => 2,
                        'status' => 'processing',
                        'completed' => in_array(
                            $this->status,
                            [
                                'processing',
                                'shipping',
                                'out_for_delivery',
                                'delivered',
                            ]
                        ),
                        'timestamp' => $this->processing_at,
                    ],

                    [
                        'position' => 3,
                        'status' => 'shipping',
                        'completed' => in_array(
                            $this->status,
                            [
                                'shipping',
                                'out_for_delivery',
                                'delivered',
                            ]
                        ),
                        'timestamp' => $this->shipping_at,
                    ],

                    [
                        'position' => 4,
                        'status' => 'out_for_delivery',
                        'completed' => in_array(
                            $this->status,
                            [
                                'out_for_delivery',
                                'delivered',
                            ]
                        ),
                        'timestamp' =>
                            $this->out_for_delivery_at,
                    ],

                    [
                        'position' => 5,
                        'status' => 'delivered',
                        'completed' =>
                            $this->status === 'delivered',
                        'timestamp' =>
                            $this->delivered_at,
                    ],
                ],
            ],
        ];
    }
}