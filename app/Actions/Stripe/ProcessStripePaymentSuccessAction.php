<?php

namespace App\Actions\Stripe;

use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Throwable;

class ProcessStripePaymentSuccessAction
{
    public function __construct(private readonly OrderRepository $orderRepository) {}

    /**
     * Process a successful Stripe payment callback.
     *
     * @param string $sessionId
     * @return array
     */
    public function __invoke(string $sessionId): array
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);
        } catch (Throwable $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'Unable to verify payment session.',
                'data' => null,
                'status' => 502
            ];
        }

        if ($session->payment_status !== 'paid') {
            return [
                'success' => false,
                'message' => 'Payment has not been completed.',
                'data' => null,
                'status' => 402
            ];
        }

        $order = $this->orderRepository->resolveFromStripeSession($session);

        if (! $order) {
            return [
                'success' => false,
                'message' => 'Order not found.',
                'data' => null,
                'status' => 404
            ];
        }

        if ($order->status === 'awaiting_payment') {
            $pi = $session->payment_intent;
            $paymentIntentId = is_string($pi) ? $pi : ($pi->id ?? null);

            DB::transaction(function () use ($order, $paymentIntentId, $session) {
                $order->refresh();
                if ($order->status !== 'awaiting_payment') {
                    return;
                }

                $this->orderRepository->markAsPlaced($order, $paymentIntentId, $session->id);
            });

            $order->refresh();
        }

        return [
            'success' => true,
            'message' => 'Payment successful. Your order has been placed.',
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
            ],
            'status' => 200,
        ];
    }
}
