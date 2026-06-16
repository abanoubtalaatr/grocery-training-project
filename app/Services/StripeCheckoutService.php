<?php

namespace App\Services;

use App\Exceptions\AmountToPayValidationException;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Models\User;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeCheckoutService
{
    public function createSessionForOrder(Order $order, User $user, float $claimedAmount): Session
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $orderTotal = (float) $order->total;
        // if (abs($orderTotal - $claimedAmount) > 0.02) {
        //     throw new \InvalidArgumentException('Amount does not match order total.');
        // }

        $currency = strtolower((string) config('services.stripe.currency', 'usd'));
        $unitAmount = (int) round($orderTotal * 100);
        // if ($unitAmount < 1) {
        //     throw new \InvalidArgumentException('Order total is too small to charge.');
        // }

        $successUrl = (string) config('services.stripe.checkout_success_url');
        $cancelUrl = (string) config('services.stripe.checkout_cancel_url');

        return Session::create([
            'mode' => 'payment',
            'client_reference_id' => (string) $order->id,
            'customer_email' => $user->email,
            'metadata' => [
                'order_id' => (string) $order->id,
                'user_id' => (string) $order->user_id,
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => $unitAmount,
                    'product_data' => [
                        'name' => 'Order '.$order->order_number,
                    ],
                ],
            ]],
            'success_url' => $successUrl,
            'cancel_url' => str_replace('{ORDER_ID}', (string) $order->id, $cancelUrl),
        ]);
    }

    public function validateTotal(float $calculatedTotal, float $providedTotal): void
    {
        if (abs($calculatedTotal - $providedTotal) > 0.1) {
            throw new AmountToPayValidationException('Provided total does not match calculated total.', 422,
                [
                    'calculated_total' => $calculatedTotal,
                    'provided_amount' => $providedTotal,
                ]);
        }
    }

    public function processPayment($user, array $validated, float $total): array
    {
        if ($validated['payment_method'] !== 'card') {
            return ['success' => true];
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        if (! $user->stripe_customer_id) {
            throw new PaymentException('Stripe customer not found. Please add a payment method first.', 400);
        }

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($total * 100),
                'currency' => 'usd',
                'customer' => $user->stripe_customer_id,
                'payment_method' => $validated['payment_method_id'],
                'off_session' => true,
                'confirm' => true,
            ]);

            if ($paymentIntent->status !== 'succeeded') {
                throw new PaymentException('Payment failed with status: '.$paymentIntent->status);
            }

            return [
                'success' => true,
                'stripe_payment_intent_id' => $paymentIntent->id,
            ];

        } catch (\Throwable $e) {
            throw new PaymentException('Payment processing failed: '.$e->getMessage(), 422);
        }
    }
}
