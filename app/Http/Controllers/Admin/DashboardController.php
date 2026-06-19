<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user() ?? \App\Models\User::first();

        // Retrieve stats
        $stats = [
            'loyalty_points' => $user?->loyalty_points ?? 0,
            'store_credits'  => $user?->store_credits ?? 0.00,
            'orders_count'   => $user ? $user->orders()->count() : 0,
            'lists_count'    => $user ? $user->smartLists()->count() : 0,
        ];

        // Retrieve recent orders
        $recentOrders = $user ? $user->orders()->latest()->limit(5)->get() : collect();

        return view('dashboard.homePage', compact('user', 'stats', 'recentOrders'));
    }
}
