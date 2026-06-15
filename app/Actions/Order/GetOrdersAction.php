<?php

namespace App\Actions\Order;

use App\Services\OrderService;

class GetOrdersAction
{
    public function execute($user)
    {
        $service = new OrderService();

        return $service->getUserOrders($user);
    }
}
