<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderNote;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderService
{
    public function __construct(
        private readonly ShippingService $shippingService
    ) {}

    public function create(
        User $user,
        array $data
    ): Order {

        return DB::transaction(function () use ($user, $data) {

            $cart = $user
                ->activeCart()
                ->with('items.meal')
                ->first();

            if (! $cart || $cart->isEmpty()) {
                throw new RuntimeException(
                    'Your cart is empty. Please add items before placing an order.'
                );
            }

            $items = $this->validateCartItems(
                $cart->items
            );

            $cart->calculateTotals();

            $shippingFee =
                $this->shippingService->calculateShippingFee(
                    (float) $cart->subtotal,
                    $data['delivery_type']
                );

            $totals = [
                'subtotal' => $cart->subtotal,
                'tax' => $cart->tax,
                'discount' => $cart->discount,
                'shipping_fee' => $shippingFee,
                'total' =>
                    (float) $cart->subtotal +
                    (float) $cart->tax +
                    $shippingFee,
            ];

            $order = $this->createOrderRecord(
                $user,
                $data,
                $totals
            );

            $this->createOrderItems(
                $order,
                $items
            );

            $this->saveOrderNotes(
                $order,
                $data
            );

            $this->clearCart(
                $user
            );

            return $order->load([
                'items.meal.category',
                'items.meal.subcategory',
                'address',
            ]);
        });
    }

    private function validateCartItems($cartItems): array
    {
        $items = [];

        foreach ($cartItems as $cartItem) {

            $meal = $cartItem->meal;

            if (! $meal) {
                throw new RuntimeException(
                    'One or more meals no longer exist.'
                );
            }

            if (! $meal->is_available) {
                throw new RuntimeException(
                    "Meal '{$meal->title}' is unavailable."
                );
            }

            if (
                $meal->stock_quantity <
                $cartItem->quantity
            ) {
                throw new RuntimeException(
                    "Only {$meal->stock_quantity} available for '{$meal->title}'."
                );
            }

            $maxPerProduct =
                config(
                    'cart.max_quantity_per_product',
                    10
                );

            if (
                $cartItem->quantity >
                $maxPerProduct
            ) {
                throw new RuntimeException(
                    "Maximum {$maxPerProduct} units allowed for '{$meal->title}'."
                );
            }

            $items[] = [
                'meal' => $meal,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'discount_amount' => $cartItem->discount_amount,
                'subtotal' => $cartItem->subtotal,
            ];
        }

        return $items;
    }

    private function createOrderRecord(
        User $user,
        array $data,
        array $totals
    ): Order {

        return Order::create([
            'user_id' => $user->id,

            'address_id' =>
                $data['delivery_type'] === 'delivery'
                    ? $data['address_id']
                    : null,

            'payment_method' =>
                $data['payment_method'],

            'delivery_type' =>
                $data['delivery_type'],

            'status' => 'placed',

            'subtotal' =>
                $totals['subtotal'],

            'tax' =>
                $totals['tax'],

            'discount' =>
                $totals['discount'],

            'shipping_fee' =>
                $totals['shipping_fee'],

            'total' =>
                $totals['total'],

            'notes' =>
                $data['notes'] ?? null,

            'placed_at' => now(),

            'schedule_delivery' =>
                $data['schedule_delivery']
                    ?? null,

            'delivery_speed' =>
                $data['delivery_speed']
                    ?? null,

            'estimated_delivery_time' =>
                $data['estimated_delivery_time']
                    ?? null,
        ]);
    }

    private function createOrderItems(
        Order $order,
        array $items
    ): void {

        foreach ($items as $item) {

            OrderItem::create([
                'order_id' => $order->id,

                'meal_id' =>
                    $item['meal']->id,

                'quantity' =>
                    $item['quantity'],

                'unit_price' =>
                    $item['unit_price'],

                'discount_amount' =>
                    $item['discount_amount'],

                'subtotal' =>
                    $item['subtotal'],
            ]);

            $item['meal']->decrement(
                'stock_quantity',
                $item['quantity']
            );
        }
    }

    private function saveOrderNotes(
        Order $order,
        array $data
    ): void {

        if (! empty($data['special_note_id'])) {

            OrderNote::create([
                'order_id' => $order->id,
                'special_note_id' => $data['special_note_id'],
                'notes' => $data['notes'] ?? null,
            ]);
        }

        if (! empty($data['notes'])) {

            OrderNote::create([
                'order_id' => $order->id,
                'special_note_id' => null,
                'notes' => $data['notes'],
            ]);
        }
    }

    private function clearCart(
        User $user
    ): void {

        $cart = $user
            ->activeCart()
            ->first();

        if (! $cart) {
            return;
        }

        $cart->items()->delete();

        $cart->update([
            'status' => 'completed',
        ]);
    }
}