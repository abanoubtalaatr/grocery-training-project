<?php

namespace App\Services\Admin;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderService
{
    public function paginate(
    Request $request,
    int $perPage = 10
    )
    {
        return Order::query()
            ->with('user')
            ->withCount('items')
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }


    public function updateStatus(
    Order $order,
    string $status
    ): bool {

        return $order->update([
            'status' => $status,
        ]);
    }

    public function delete(Order $order): bool
    {
        return $order->delete();
    }
}