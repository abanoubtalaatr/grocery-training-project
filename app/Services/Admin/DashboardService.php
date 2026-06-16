<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Meal;
use App\Models\Order;
use App\Models\User;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'users' => User::count(),
            'orders' => Order::count(),
            'meals' => Meal::count(),
            'categories' => Category::count(),

            'recent_users' => User::latest()
                ->take(5)
                ->get(),

            'recent_orders' => Order::latest()
                ->with('user')
                ->take(5)
                ->get(),
        ];
    }
}