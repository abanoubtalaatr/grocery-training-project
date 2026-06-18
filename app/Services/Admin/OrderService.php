<?php

namespace App\Services\Admin;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderService
{
    public function paginate(int $perPage = 20, ?string $q = null): LengthAwarePaginator
    {
        $query = Order::orderBy('placed_at', 'desc');
        if ($q) {
            $query->where('order_number', 'like', '%'.$q.'%')
                  ->orWhereHas('user', function ($u) use ($q) {
                      $u->where('name', 'like', '%'.$q.'%');
                  });
        }
        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Order
    {
        return Order::find($id);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        return $order->refresh();
    }
}
