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

            'active_users' => User::where(
                'is_active',
                true
            )->count(),

            'available_meals' => Meal::where(
                'is_available',
                true
            )->count(),

            'active_categories' => Category::where(
                'is_active',
                true
            )->count(),

            'total_sales' => Order::sum('total'),

            'latest_orders' => Order::with('user')
                ->latest()
                ->take(5)
                ->get(),
        ];
    }
}