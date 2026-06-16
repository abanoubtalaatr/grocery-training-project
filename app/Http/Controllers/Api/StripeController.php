<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    public function createSetupIntent(Request $request): JsonResponse
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $user = $request->user();

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

        return self::successResponse('Setup intent created successfully', ['clientSecret' => $intent->client_secret]);
    }

    public function listCards(Request $request): JsonResponse
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $user = $request->user();

        if (!$user->stripe_customer_id) {
            return self::collectionResponse('No cards found', []);
        }

        $cards = PaymentMethod::all([
            'customer' => $user->stripe_customer_id,
            'type' => 'card',
        ]);

        return self::collectionResponse('Cards retrieved successfully', $cards->data);
    }

    public function chargeSavedCard(Request $request): JsonResponse
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));
        $user = $request->user();

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100,
            'currency' => 'usd',
            'customer' => $user->stripe_customer_id,
            'payment_method' => $request->payment_method_id,
            'off_session' => true,
            'confirm' => true,
        ]);

        return self::successResponse('Payment successful', ['payment_intent' => $paymentIntent]);
    }

    public function deleteCard(Request $request, $id): JsonResponse
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethod = PaymentMethod::retrieve($id);
        $paymentMethod->detach();

        return self::successResponse('Card deleted successfully');
    }
}
