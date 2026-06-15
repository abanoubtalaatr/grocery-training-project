<?php

namespace App\Http\Controllers\Api;

use App\Actions\Order\CreateStripeSessionAction;
use App\Actions\Order\VerifyStripeSessionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStripeCheckoutSessionRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StripeCheckoutController extends Controller
{
    /**
     * Verify checkout session payment status.
     */
    public function verifySession(Request $request, string $sessionId): JsonResponse
    {
        $order = VerifyStripeSessionAction::run($request->user(), $sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified. Order is placed.',
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
            ],
        ]);
    }

    /**
     * Create checkout session for payment.
     */
    public function store(CreateStripeCheckoutSessionRequest $request): JsonResponse
    {
        $user = $request->user();
        $order = Order::query()
            ->whereKey($request->validated('order_id'))
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        $sessionData = CreateStripeSessionAction::run($order, $user, (float) $request->validated('amount'));

        return response()->json([
            'success' => true,
            'message' => 'Checkout session created. Open checkout_url in your WebView.',
            'data' => [
                'checkout_url' => $sessionData['checkout_url'],
                'session_id' => $sessionData['session_id'],
                'order_id' => $sessionData['order_id'],
            ],
        ]);
    }
}
