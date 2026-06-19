<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function index(Request $request)
    {
        $orders = $this->orderService->paginate($request);

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.meal',
            'address',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    public function edit(Order $order)
    {
        return view(
            'admin.orders.edit',
            compact('order')
        );
    }

    public function update(
        UpdateOrderStatusRequest $request,
        Order $order
    ) {
        $this->orderService->updateStatus(
            $order,
            $request->status
        );

        return redirect()
            ->route('admin.orders.index')
            ->with(
                'success',
                'Order status updated successfully.'
            );
    }

    public function destroy(Order $order)
    {
        $this->orderService->delete($order);

        return redirect()
            ->route('admin.orders.index')
            ->with(
                'success',
                'Order deleted successfully.'
            );
    }
}