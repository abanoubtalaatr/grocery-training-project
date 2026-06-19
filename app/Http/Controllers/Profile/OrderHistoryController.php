<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\OrderHistoryService;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function __construct(
        protected OrderHistoryService $orderHistoryService
    ) {}

    /**
     * Display order history page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $orders = $this->orderHistoryService->getOrderHistory($user);
            
        return view('dashboard.order-history', compact('user', 'orders'));
    }
}
