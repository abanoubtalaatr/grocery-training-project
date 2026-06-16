<?php

namespace App\Actions\Order;

use App\Models\User;
use App\Repositories\OrderRepository;
use App\Traits\FormatsOrder;

class GetOrdersAction
{
    use FormatsOrder;

    public function __construct(private readonly OrderRepository $orderRepository) {}

    public function __invoke(User $user)
    {
        return $this->orderRepository->getForUser($user)->map(function ($order) {
            return $this->formatOrder($order);
        });
    }
}
