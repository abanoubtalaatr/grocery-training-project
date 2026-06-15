<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentHistoryResource;
use App\Http\Resources\ReceiptResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Get payment history for the authenticated user.
     */
    public function paymentHistory(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->where('status', '!=', 'cancelled')
            ->with(['items.meal.category', 'address'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Payment history retrieved successfully',
            'data' => PaymentHistoryResource::collection($orders),
            'total_count' => $orders->count(),
            'total_amount' => (float) $orders->sum('total'),
        ]);
    }

    /**
     * Get receipt/invoice for a specific order.
     */
    public function receipt(Request $request, Order $order): JsonResponse
    {
        $this->authorizeOwner($request, $order);

        $order->load(['items.meal.category', 'items.meal.subcategory', 'address', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Receipt retrieved successfully',
            'data' => new ReceiptResource($order),
        ]);
    }

    /**
     * Get invoice for a specific order (alias for receipt).
     */
    public function invoice(Request $request, Order $order): JsonResponse
    {
        return $this->receipt($request, $order);
    }

    /**
     * Authorize that the order belongs to the user.
     */
    private function authorizeOwner(Request $request, Order $order): void
    {
        if ($order->user_id !== $request->user()->id) {
            abort(404, 'Order not found');
        }
    }
}
