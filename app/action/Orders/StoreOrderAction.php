<?php

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\OrderNote;
use App\Models\User;
use App\Services\ShippingService;
use Illuminate\Support\Facades\DB;

class StoreOrderAction
{
    public function execute(User $user, array $validated): array
    {
        $cart = $user->activeCart()->with('items.meal')->first();

        if (!$cart || $cart->isEmpty()) {
            return $this->fail('Your cart is empty. Please add items to your cart before placing an order.', 400);
        }

        $itemsResult = $this->validateAndProcessCartItems($cart->items);
        if (!$itemsResult['success']) {
            return ['success' => false, 'response' => $itemsResult['response'], 'status' => 400];
        }

        $items = $itemsResult['items'];

        $cart->calculateTotals();
        $shippingService = app(ShippingService::class);
        $shippingFee = $shippingService->calculateShippingFee((float) $cart->subtotal, $validated['delivery_type']);

        $totals = [
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax,
            'discount' => $cart->discount,
            'shipping_fee' => $shippingFee,
            'total' => (float) $cart->subtotal + (float) $cart->tax + $shippingFee,
        ];

        DB::beginTransaction();

        try {
            $paymentResult = match ($validated['payment_method']) {
                'stripe_checkout' => ['success' => true],
                default => $this->processPayment($user, $validated, $totals['total']),
            };

            if (!$paymentResult['success']) {
                DB::rollBack();
                return ['success' => false, 'response' => $paymentResult['response'], 'status' => 400];
            }

            $stripePaymentIntentId = $paymentResult['stripe_payment_intent_id'] ?? null;

            $order = $this->createOrder($user, $validated, $totals['subtotal'], $totals, $stripePaymentIntentId);

            $this->createOrderItems($order, $items);

            $this->clearUserCart($user);

            if (isset($validated['special_note_id'])) {
                OrderNote::create([
                    'order_id' => $order->id,
                    'special_note_id' => $validated['special_note_id'],
                    'notes' => $validated['notes'] ?? null,
                ]);
            } elseif (isset($validated['notes'])) {
                OrderNote::create([
                    'order_id' => $order->id,
                    'special_note_id' => null,
                    'notes' => $validated['notes'],
                ]);
            }

            DB::commit();

            $order->load(['items.meal', 'address']);

            return ['success' => true, 'order' => $order, 'status' => 201];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function fail(string $message, int $status): array
    {
        return ['success' => false, 'response' => ['success' => false, 'message' => $message], 'status' => $status];
    }

   protected function validateAndProcessCartItems($cartItems): array
{
    // TODO: implement real validation
    return ['success' => true, 'items' => $cartItems];
}

protected function processPayment(User $user, array $validated, float $total): array
{
    // TODO: implement real payment processing
    return ['success' => true];
}

protected function createOrder(User $user, array $validated, $subtotal, array $totals, ?string $stripePaymentIntentId): Order
{
    // TODO: implement real order creation
    return Order::create([
        'user_id' => $user->id,
        'subtotal' => $subtotal,
        'tax' => $totals['tax'],
        'discount' => $totals['discount'],
        'shipping_fee' => $totals['shipping_fee'],
        'total' => $totals['total'],
        'payment_method' => $validated['payment_method'],
        'stripe_payment_intent_id' => $stripePaymentIntentId,
        'delivery_type' => $validated['delivery_type'],
    ]);
}

    protected function createOrderItems(Order $order, array $items): void
    {
        // move existing logic here from controller
    }

    protected function clearUserCart(User $user): void
    {
        // move existing logic here from controller
    }
}