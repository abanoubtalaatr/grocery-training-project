<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function index(Request $request)
    {
        $orders = $this->orderService->paginate(
            search: $request->search
        );

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }
}