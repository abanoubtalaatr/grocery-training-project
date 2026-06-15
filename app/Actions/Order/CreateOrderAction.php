<?php

namespace App\Actions\Order;

use App\Services\OrderService;

class CreateOrderAction
{
    public function execute($user, array $validated)
    {
        $service = new OrderService();

        return $service->createOrder($user, $validated);
    }
}
