<?php

namespace App\Actions\Payment;

use Stripe\PaymentMethod;
use Stripe\Stripe;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteSavedCardAction
{
    use AsAction;

    /**
     * Handle detaching/deleting a saved card.
     */
    public function handle(string $paymentMethodId): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->detach();
    }
}
