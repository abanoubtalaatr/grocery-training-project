<?php

namespace App\Services\Admin;

use App\Models\Order;

class OrderService
{
    public function paginate(?string $search = null, int $perPage = 10)
    {
        return Order::query()
            ->with('user')
            ->withCount('items')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'order_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas('user', function ($userQuery) use ($search) {

                        $userQuery
                            ->where('firstname', 'like', "%{$search}%")
                            ->orWhere('lastname', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");

                    });

                });

            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}