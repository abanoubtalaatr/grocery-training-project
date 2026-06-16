<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Traits\FormatsOrder;

class GetOrderAction
{
    use FormatsOrder;

    public function __invoke(Order $order): array
    {
        $order = $order->load(['items.meal', 'address']);
        
        return $this->formatOrder($order);
    }
}
