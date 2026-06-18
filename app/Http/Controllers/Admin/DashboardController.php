<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\SmartList;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $categoriesCount = Category::count();
        $ordersCount = Order::count();
        $smartlistsCount = SmartList::count();

        $recentCategories = Category::ordered()->limit(5)->get();
        $recentOrders = Order::orderBy('placed_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact('categoriesCount', 'ordersCount', 'smartlistsCount', 'recentCategories', 'recentOrders'));
    }
}
