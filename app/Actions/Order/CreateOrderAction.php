<?php

namespace App\Actions\Order;

use App\Exceptions\BusinessException;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderNote;
use App\Models\User;
use App\Services\ShippingService;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOrderAction
{
    use Action; // Wait, let's use AsAction! Oh, PSR-12 and trait usage: AsAction.

    use AsAction;

    /**
     * Handle order checkout creation.
     *
     * @throws BusinessException
     */
    public function handle(User $user, array $validated): Order
    {
        // 1. Get user's active cart
        $cart = $user->activeCart()->with('items.meal')->first();

        if (!$cart || $cart->isEmpty()) {
            throw new BusinessException('Your cart is empty. Please add items to your cart before placing an order.');
        }

        // 2. Validate and process items from cart
        $items = [];
        foreach ($cart->items as $cartItem) {
            $meal = $cartItem->meal;

            if (!$meal) {
                throw new BusinessException('One or more items in your cart are no longer available.');
            }

            if (!$meal->is_available) {
                throw new BusinessException("Meal '{$meal->title}' is currently unavailable");
            }

            if ($meal->stock_quantity < $cartItem->quantity) {
                throw new BusinessException("Only {$meal->stock_quantity} items available for '{$meal->title}'");
            }

            $maxPerProduct = config('cart.max_quantity_per_product', 10);
            if ($cartItem->quantity > $maxPerProduct) {
                throw new BusinessException("Maximum {$maxPerProduct} units per product allowed. Please reduce quantity for '{$meal->title}'.");
            }

            $items[] = [
                'meal' => $meal,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'discount_amount' => $cartItem->discount_amount,
                'subtotal' => $cartItem->subtotal,
            ];
        }

        // 3. Recalculate totals
        $cart->calculateTotals();
        $shippingService = app(ShippingService::class);
        $shippingFee = $shippingService->calculateShippingFee((float) $cart->subtotal, $validated['delivery_type']);
        
        $totals = [
            'subtotal' => (float) $cart->subtotal,
            'tax' => (float) $cart->tax,
            'discount' => (float) $cart->discount,
            'shipping_fee' => (float) $shippingFee,
            'total' => (float) $cart->subtotal + (float) $cart->tax + (float) $shippingFee,
        ];
        $total = $totals['total'];

        // 4. Process payment for direct card method
        $stripePaymentIntentId = null;
        if ($validated['payment_method'] === 'card') {
            if (!$user->stripe_customer_id) {
                throw new BusinessException('Stripe customer not found. Please add a payment method first.');
            }

            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int)($total * 100),
                    'currency' => 'usd',
                    'customer' => $user->stripe_customer_id,
                    'payment_method' => $validated['payment_method_id'],
                    'off_session' => true,
                    'confirm' => true,
                ]);

                if ($paymentIntent->status !== 'succeeded') {
                    throw new BusinessException('Payment failed: ' . $paymentIntent->status);
                }

                $stripePaymentIntentId = $paymentIntent->id;
            } catch (\Exception $e) {
                throw new BusinessException('Payment processing failed: ' . $e->getMessage());
            }
        }

        // 5. DB transaction for order recording
        return DB::transaction(function () use ($user, $validated, $totals, $stripePaymentIntentId, $items, $cart) {
            $isHostedStripe = $validated['payment_method'] === 'stripe_checkout';

            // Create order
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
                'schedule_delivery' => $validated['schedule_delivery'] ?? null,
                'delivery_speed' => $validated['delivery_speed'] ?? null,
                'estimated_delivery_time' => $validated['estimated_delivery_time'] ?? null,
            ]);

            // Create order items and decrement stock
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

            // Clear active cart
            $cart->items()->delete();
            $cart->update(['status' => 'completed']);

            // Special notes
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

            $order->load(['items.meal', 'address']);

            return $order;
        });
    }
}
