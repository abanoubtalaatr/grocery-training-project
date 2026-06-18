<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderRequest;
use App\Models\Order;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $q = $request->get('q');
        $orders = $this->service->paginate(20, $q);
        return view('admin.orders.index', compact('orders', 'q'));
    }

    public function show(Order $order)
    {
        $order->load('items', 'notes', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function update(OrderRequest $request, Order $order)
    {
        $data = $request->validated();
        $this->service->update($order, $data);
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated');
    }
}
