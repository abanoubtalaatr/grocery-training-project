<?php

namespace App\Actions\Order;

use App\Exceptions\BusinessException;
use App\Models\Order;
use App\Models\User;
use App\Services\StripeCheckoutService;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateStripeSessionAction
{
    use AsAction;

    /**
     * Handle creating a Stripe Checkout Session for an order.
     *
     * @throws BusinessException
     */
    public function handle(Order $order, User $user, float $amount): array
    {
        try {
            $checkoutService = app(StripeCheckoutService::class);
            $session = $checkoutService->createSessionForOrder($order, $user, $amount);

            $order->update(['stripe_checkout_session_id' => $session->id]);

            return [
                'checkout_url' => $session->url,
                'session_id' => $session->id,
                'order_id' => $order->id,
            ];
        } catch (\InvalidArgumentException $e) {
            throw new BusinessException($e->getMessage(), 422);
        } catch (\Throwable $e) {
            report($e);
            throw new BusinessException('Unable to start checkout. Please try again.', 502);
        }
    }
}
