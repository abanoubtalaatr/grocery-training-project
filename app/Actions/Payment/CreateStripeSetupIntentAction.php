<?php

namespace App\Actions\Payment;

use App\Models\User;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateStripeSetupIntentAction
{
    use AsAction;

    /**
     * Handle creating a Stripe Setup Intent.
     */
    public function handle(User $user): string
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if (!$user->stripe_customer_id) {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);
            $user->update(['stripe_customer_id' => $customer->id]);
        }

        $intent = SetupIntent::create([
            'customer' => $user->stripe_customer_id,
            'payment_method_types' => ['card'],
        ]);

        return $intent->client_secret;
    }
}
