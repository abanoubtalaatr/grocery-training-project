<?php

namespace App\Actions\Order;

use App\Services\OrderService;

class TrackOrderAction
{
    public function execute($user)
    {
        $service = new OrderService();

        return $service->trackLastActiveOrder($user);
    }
}
