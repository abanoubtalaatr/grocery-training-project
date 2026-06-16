<?php

namespace App\Http\Actions\Api\Order;

use App\Models\Order;

class GetOrderAction
{
    public function execute(
        Order $order
    ): Order {

        return $order->load([
            'items.meal.category',
            'items.meal.subcategory',
            'address',
        ]);
    }
}