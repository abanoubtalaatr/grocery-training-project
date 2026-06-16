<?php

namespace App\Http\Actions\Api\Payment;

use App\Models\Order;
use App\Models\User;

class GetReceiptAction
{
    public function execute(
        User $user,
        Order $order
    ): Order {

        abort_if(
            $order->user_id !== $user->id,
            404,
            'Order not found'
        );

        return $order->load([
            'items.meal.category',
            'items.meal.subcategory',
            'address',
            'user',
        ]);
    }
}