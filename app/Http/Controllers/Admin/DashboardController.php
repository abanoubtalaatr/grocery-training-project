<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Meal;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::count(),
            'meals' => Meal::count(),
            'orders' => Order::count(),
            'reviews' => Review::count(),
            'active_orders' => Order::active()->count(),
            'new_messages' => ContactMessage::new()->count(),
            'pending_reviews' => Review::pending()->count(),
            'revenue' => (float) Order::where('status', 'delivered')->sum('total'),
        ];

        $recentOrders = Order::with('user')->latest()->take(8)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers'));
    }
}
