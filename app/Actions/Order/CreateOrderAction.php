<?php

namespace App\Actions\Order;

use App\Models\User;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\ShippingService;
use App\Traits\FormatsOrder;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Exception;

class CreateOrderAction
{
    use FormatsOrder;

    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly ShippingService $shippingService
    ) {}

    public function __invoke(User $user, array $validated): array
    {
        $cart = $user->activeCart()->with('items.meal')->first();

        if (!$cart || $cart->isEmpty()) {
            throw new Exception('Your cart is empty. Please add items to your cart before placing an order.', 400);
        }

        $itemsResult = $this->validateAndProcessCartItems($cart->items);
        
        $items = $itemsResult['items'];

        $cart->calculateTotals();
        $shippingFee = $this->shippingService->calculateShippingFee((float) $cart->subtotal, $validated['delivery_type']);
        
        $totals = [
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax,
            'discount' => $cart->discount,
            'shipping_fee' => $shippingFee,
            'total' => (float) $cart->subtotal + (float) $cart->tax + $shippingFee,
        ];
        
        $total = $totals['total'];

        return DB::transaction(function () use ($user, $validated, $totals, $items, $cart, $total) {
            $paymentResult = match ($validated['payment_method']) {
                'stripe_checkout' => ['success' => true],
                default => $this->processPayment($user, $validated, $total),
            };

            if (!$paymentResult['success']) {
                throw new Exception($paymentResult['response']['message'], 400);
            }

            $stripePaymentIntentId = $paymentResult['stripe_payment_intent_id'] ?? null;

            $order = $this->createOrderRecord($user, $validated, $totals, $stripePaymentIntentId);

            $this->createOrderItems($order, $items);

            $this->clearUserCart($cart);

            if (isset($validated['special_note_id'])) {
                $this->orderRepository->createNote([
                    'order_id' => $order->id,
                    'special_note_id' => $validated['special_note_id'],
                    'notes' => $validated['notes'] ?? null,
                ]);
            }
            if (isset($validated['notes'])) {
                $this->orderRepository->createNote([
                    'order_id' => $order->id,
                    'special_note_id' => null,
                    'notes' => $validated['notes'],
                ]);
            }

            $order->load(['items.meal', 'address']);

            return $this->formatOrder($order);
        });
    }

    private function validateAndProcessCartItems($cartItems): array
    {
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $cartItem) {
            $meal = $cartItem->meal;

            if (!$meal) {
                throw new Exception('One or more items in your cart are no longer available.', 400);
            }

            if (!$meal->is_available) {
                throw new Exception("Meal '{$meal->title}' is currently unavailable", 400);
            }

            if ($meal->stock_quantity < $cartItem->quantity) {
                throw new Exception("Only {$meal->stock_quantity} items available for '{$meal->title}'", 400);
            }

            $maxPerProduct = config('cart.max_quantity_per_product', 10);
            if ($cartItem->quantity > $maxPerProduct) {
                throw new Exception("Maximum {$maxPerProduct} units per product allowed. Please reduce quantity for '{$meal->title}'.", 400);
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

        return [
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
        ];
    }

    private function processPayment($user, array $validated, float $total): array
    {
        if ($validated['payment_method'] !== 'card') {
            return ['success' => true];
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        if (!$user->stripe_customer_id) {
            return [
                'success' => false,
                'response' => [
                    'success' => false,
                    'message' => 'Stripe customer not found. Please add a payment method first.',
                ],
            ];
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
                return [
                    'success' => false,
                    'response' => [
                        'success' => false,
                        'message' => 'Payment failed: ' . $paymentIntent->status,
                    ],
                ];
            }

            return [
                'success' => true,
                'stripe_payment_intent_id' => $paymentIntent->id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'response' => [
                    'success' => false,
                    'message' => 'Payment processing failed: ' . $e->getMessage(),
                ],
            ];
        }
    }

    private function createOrderRecord($user, array $validated, array $totals, ?string $stripePaymentIntentId = null): Order
    {
        $isHostedStripe = $validated['payment_method'] === 'stripe_checkout';

        return $this->orderRepository->create([
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
    }

    private function createOrderItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $this->orderRepository->createItem([
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

    private function clearUserCart($cart): void
    {
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['status' => 'completed']);
        }
    }
}
