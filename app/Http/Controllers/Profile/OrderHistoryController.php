<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    /**
     * Display order history page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $orders = Order::where('user_id', $user->id)
            ->with(['items.meal.category', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboard.order-history', compact('user', 'orders'));
    }
}
