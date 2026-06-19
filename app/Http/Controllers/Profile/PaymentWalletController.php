<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentWalletController extends Controller
{
    /**
     * Display payment & wallet page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $orders = Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->with(['items.meal.category', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalAmount = (float) $orders->sum('total');
        
        return view('dashboard.payment-wallet', compact('user', 'orders', 'totalAmount'));
    }
}
