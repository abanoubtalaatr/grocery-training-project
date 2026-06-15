<?php

namespace App\Actions\Order;

use App\Exceptions\BusinessException;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Lorisleiva\Actions\Concerns\AsAction;

class VerifyStripeSessionAction
{
    use AsAction;

    /**
     * Handle verifying a Stripe session status and completing the order.
     *
     * @throws BusinessException
     */
    public function handle(User $user, string $sessionId): Order
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);
        } catch (\Throwable $e) {
            report($e);
            throw new BusinessException('Unable to verify payment session.', 502);
        }

        if ($session->payment_status !== 'paid') {
            throw new BusinessException('Payment has not been completed.', 402, [
                'payment_status' => $session->payment_status,
            ]);
        }

        $orderId = $session->metadata->order_id ?? $session->client_reference_id ?? null;
        $order = $orderId
            ? Order::query()->whereKey((int) $orderId)->where('user_id', $user->id)->first()
            : null;

        if (!$order) {
            throw new BusinessException('Order not found.', 404);
        }

        if ($order->status === 'awaiting_payment') {
            $pi = $session->payment_intent;
            $paymentIntentId = is_string($pi) ? $pi : ($pi->id ?? null);

            DB::transaction(function () use ($order, $paymentIntentId, $session) {
                $order->refresh();
                if ($order->status !== 'awaiting_payment') {
                    return;
                }

                $order->update([
                    'status' => 'placed',
                    'placed_at' => now(),
                    'stripe_payment_intent_id' => $paymentIntentId,
                    'stripe_checkout_session_id' => $session->id,
                ]);
            });

            $order->refresh();
        }

        return $order;
    }
}
