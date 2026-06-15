<?php

namespace App\Http\Controllers\Api;

use App\Actions\Payment\ChargeSavedCardAction;
use App\Actions\Payment\CreateStripeSetupIntentAction;
use App\Actions\Payment\DeleteSavedCardAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeSavedCardRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * Create Stripe setup intent.
     */
    public function createSetupIntent(Request $request): JsonResponse
    {
        $clientSecret = CreateStripeSetupIntentAction::run($request->user());

        return response()->json(['clientSecret' => $clientSecret]);
    }

    /**
     * List user's saved cards.
     */
    public function listCards(Request $request): JsonResponse
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $user = $request->user();

        if (!$user->stripe_customer_id) {
            return response()->json([]);
        }

        $cards = PaymentMethod::all([
            'customer' => $user->stripe_customer_id,
            'type' => 'card',
        ]);

        return response()->json($cards->data);
    }

    /**
     * Charge a user's saved card.
     */
    public function chargeSavedCard(ChargeSavedCardRequest $request): JsonResponse
    {
        $paymentIntent = ChargeSavedCardAction::run(
            $request->user(),
            $request->validated('payment_method_id'),
            (float) $request->validated('amount')
        );

        return response()->json([
            'status' => 'success',
            'payment_intent' => $paymentIntent,
        ]);
    }

    /**
     * Delete/detach a saved card.
     */
    public function deleteCard(Request $request, string $id): JsonResponse
    {
        DeleteSavedCardAction::run($id);

        return response()->json(['status' => 'deleted']);
    }
}
