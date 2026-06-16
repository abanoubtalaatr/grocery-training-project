<?php

namespace App\Http\Controllers;

use App\Actions\Stripe\ProcessStripePaymentSuccessAction;
use App\Http\Requests\Stripe\StripePaymentSuccessRequest;
use Illuminate\Http\Request;

class StripePaymentCallbackController extends Controller
{
    public function success(StripePaymentSuccessRequest $request, ProcessStripePaymentSuccessAction $action)
    {
        $validated = $request->validated();

        $result = $action($validated['session_id']);

        return $this->jsonOrHtml(
            $result['success'],
            $result['message'],
            $result['data'],
            $result['status']
        );
    }

    public function cancel(Request $request)
    {
        $orderId = $request->query('order_id');

        return $this->jsonOrHtml(false, 'Payment was cancelled.', [
            'order_id' => $orderId,
        ], 200);
    }

    private function jsonOrHtml(bool $success, string $message, ?array $data, int $status)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
