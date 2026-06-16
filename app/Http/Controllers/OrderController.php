<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->latest()
            ->paginate(15);

        return view('admins.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admins.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admins.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $this->validatedData($request);

        $order->update($data);

        return redirect()
            ->route('admins.orders.show', $order)
            ->with('status', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('admins.orders.index')
            ->with('status', 'Order deleted successfully.');
    }

    public function massDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:orders,id'],
        ]);

        Order::whereIn('id', $data['ids'])->delete();

        return redirect()
            ->route('admins.orders.index')
            ->with('status', 'Selected orders deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'status' => ['required', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'delivery_type' => ['nullable', 'string', 'max:255'],
            'subtotal' => ['nullable', 'numeric'],
            'tax' => ['nullable', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'shipping_fee' => ['nullable', 'numeric'],
            'total' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
            'schedule_delivery' => ['nullable'],
            'delivery_speed' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
