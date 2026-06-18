<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Status to timestamp column mapping for the order lifecycle.
     *
     * @var array<string, string>
     */
    private const STATUS_TIMESTAMPS = [
        'placed' => 'placed_at',
        'processing' => 'processing_at',
        'shipping' => 'shipping_at',
        'out_for_delivery' => 'out_for_delivery_at',
        'delivered' => 'delivered_at',
        'cancelled' => 'cancelled_at',
    ];

    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with('user')
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('order_number', 'like', "%{$search}%"))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => $this->statusLabels(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'address', 'items.meal']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => $this->statusLabels(),
        ]);
    }

    public function updateStatus(OrderStatusRequest $request, Order $order): RedirectResponse
    {
        $status = $request->validated()['status'];

        $attributes = ['status' => $status];

        if (isset(self::STATUS_TIMESTAMPS[$status]) && $order->{self::STATUS_TIMESTAMPS[$status]} === null) {
            $attributes[self::STATUS_TIMESTAMPS[$status]] = now();
        }

        $order->update($attributes);

        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
    {
        return [
            'awaiting_payment' => 'Awaiting payment',
            'placed' => 'Order placed',
            'processing' => 'Processing',
            'shipping' => 'Shipping',
            'out_for_delivery' => 'Out for delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];
    }
}
