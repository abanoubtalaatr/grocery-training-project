<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderNote;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderService
{
    public function createOrder($user, array $validated): Order
    {
        $cart = $user->activeCart()->with('items.meal')->first();

        if (!$cart || $cart->isEmpty()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Your cart is empty. Please add items to your cart before placing an order.',
            ], 400));
        }

        $itemsResult = $this->validateCartItems($cart->items);

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

        $stripePaymentIntentId = $this->processPayment($user, $validated, $totals['total']);

        return DB::transaction(function () use ($user, $validated, $totals, $items, $stripePaymentIntentId) {
            $isHostedStripe = $validated['payment_method'] === 'stripe_checkout';

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $validated['delivery_type'] === 'delivery' ? $validated['address_id'] : null,
                'payment_method' => $validated['payment_method'],
                'payment_method_id' => null,
                'stripe_payment_intent_id' => $stripePaymentIntentId,
                'delivery_type' => $validated['delivery_type'],
                'status' => $isHostedStripe ? 'awaiting_payment' : 'placed',
                'subtotal' => $totals['subtotal'],
                'tax' => $totals['tax'],
                'discount' => $totals['discount'],
                'shipping_fee' => $totals['shipping_fee'],
                'total' => $totals['total'],
                'notes' => $validated['notes'] ?? null,
                'placed_at' => $isHostedStripe ? null : now(),
            ]);

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

            // Clear user's active cart
            $cart = $user->activeCart()->first();
            if ($cart) {
                $cart->items()->delete();
                $cart->update(['status' => 'completed']);
            }

            if (isset($validated['special_note_id'])) {
                OrderNote::create([
                    'order_id' => $order->id,
                    'special_note_id' => $validated['special_note_id'],
                    'notes' => $validated['notes'] ?? null,
                ]);
            }
            if (isset($validated['notes'])) {
                OrderNote::create([
                    'order_id' => $order->id,
                    'special_note_id' => null,
                    'notes' => $validated['notes'],
                ]);
            }

            return $order;
        });
    }

    private function validateCartItems($cartItems): array
    {
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $cartItem) {
            $meal = $cartItem->meal;

            if (!$meal) {
                throw new HttpResponseException(response()->json(['success' => false, 'message' => 'One or more items in your cart are no longer available.'], 400));
            }

            if (!$meal->is_available) {
                throw new HttpResponseException(response()->json(['success' => false, 'message' => "Meal '{$meal->title}' is currently unavailable"], 400));
            }

            if ($meal->stock_quantity < $cartItem->quantity) {
                throw new HttpResponseException(response()->json(['success' => false, 'message' => "Only {$meal->stock_quantity} items available for '{$meal->title}'"], 400));
            }

            $maxPerProduct = config('cart.max_quantity_per_product', 10);
            if ($cartItem->quantity > $maxPerProduct) {
                throw new HttpResponseException(response()->json(['success' => false, 'message' => "Maximum {$maxPerProduct} units per product allowed. Please reduce quantity for '{$meal->title}'."], 400));
            }

            $items[] = [
                'meal' => $meal,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'discount_amount' => $cartItem->discount_amount,
                'subtotal' => $cartItem->subtotal,
            ];

            $subtotal += $cartItem->subtotal;
        }

        return ['items' => $items, 'subtotal' => $subtotal];
    }

    private function processPayment($user, array $validated, float $total): ?string
    {
        if (($validated['payment_method'] ?? null) !== 'card') {
            return null;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        if (!$user->stripe_customer_id) {
            throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Stripe customer not found. Please add a payment method first.'], 400));
        }

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($total * 100),
                'currency' => 'usd',
                'customer' => $user->stripe_customer_id,
                'payment_method' => $validated['payment_method_id'],
                'off_session' => true,
                'confirm' => true,
            ]);

            if ($paymentIntent->status !== 'succeeded') {
                throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Payment failed: ' . $paymentIntent->status], 400));
            }

            return $paymentIntent->id;
        } catch (HttpResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Payment processing failed: ' . $e->getMessage()], 400));
        }
    }

    public function getUserOrders($user)
    {
        return Order::with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function trackLastActiveOrder($user)
    {
        return Order::where('user_id', $user->id)
            ->whereNotIn('status', ['cancelled', 'delivered'])
            ->with(['items.meal.category', 'items.meal.subcategory', 'address'])
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
