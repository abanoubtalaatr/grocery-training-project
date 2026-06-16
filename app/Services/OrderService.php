<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderNote;

class OrderService
{
    public function createOrder($user, array $validated, float $subtotal, array $totals, ?string $stripePaymentIntentId = null): Order
    {
        $isHostedStripe = $validated['payment_method'] === 'stripe_checkout';

        return Order::create([
            'user_id' => $user->id,
            'address_id' => $validated['delivery_type'] === 'delivery' ? $validated['address_id'] : null,
            'payment_method' => $validated['payment_method'],
            'payment_method_id' => null,
            'stripe_payment_intent_id' => $stripePaymentIntentId,
            'delivery_type' => $validated['delivery_type'],
            'status' => $isHostedStripe ? 'awaiting_payment' : 'placed',
            'subtotal' => $subtotal,
            'tax' => $totals['tax'],
            'discount' => $totals['discount'],
            'shipping_fee' => $totals['shipping_fee'],
            'total' => $totals['total'],
            'notes' => $validated['notes'] ?? null,
            'placed_at' => $isHostedStripe ? null : now(),
        ]);
    }

    public function createOrderItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'meal_id' => $item['meal']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount_amount' => $item['discount_amount'],
                'subtotal' => $item['subtotal'],
            ]);

            $item['meal']->decrement('stock_quantity', $item['quantity']);
        }
    }

    public function handleOrderNotes(Order $order, array $validated): void
    {
        if (! empty($validated['special_note_id']) || ! empty($validated['notes'])) {
            OrderNote::create([
                'order_id' => $order->id,
                'special_note_id' => $validated['special_note_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        }
    }
}
