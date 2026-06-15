<?php

namespace App\Actions\Payment;

use App\Models\User;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Lorisleiva\Actions\Concerns\AsAction;

class ChargeSavedCardAction
{
    use AsAction;

    /**
     * Handle charging a user's saved card.
     */
    public function handle(User $user, string $paymentMethodId, float $amount): PaymentIntent
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        return PaymentIntent::create([
            'amount' => (int)($amount * 100),
            'currency' => 'usd',
            'customer' => $user->stripe_customer_id,
            'payment_method' => $paymentMethodId,
            'off_session' => true,
            'confirm' => true,
        ]);
    }
}
