<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderNote;
use App\Models\OrderItem;

class OrderRepository
{
    public function getForUser(User $user)
    {
        return Order::with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findActiveForUser(User $user)
    {
        return Order::where('user_id', $user->id)
            ->whereNotIn('status', ['cancelled', 'delivered'])
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function createNote(array $data)
    {
        return OrderNote::create($data);
    }

    public function createItem(array $data)
    {
        return OrderItem::create($data);
    }
}
