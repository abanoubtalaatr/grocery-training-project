<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Services\OrderHistoryService;
use Illuminate\Http\Request;

class PaymentWalletController extends Controller
{
    public function __construct(
        protected OrderHistoryService $orderHistoryService
    ) {}

    /**
     * Display payment & wallet page.
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        $orders = $this->orderHistoryService->getPaymentHistory($user);

        $totalAmount = (float) $orders->sum('total');

        return view('dashboard.payment-wallet', compact('user', 'orders', 'totalAmount'));
    }
}
