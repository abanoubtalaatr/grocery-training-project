<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;

class PaymentController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Get payment history for the authenticated user.
     */
    public function paymentHistory(Request $request): JsonResponse
    {
        $orders = $this->paymentService->getPaymentHistory($request->user());
        $paymentHistory = $this->paymentService->formatPaymentHistory($orders);

        return self::collectionResponse('Payment history retrieved successfully', $paymentHistory);
    }

    /**
     * Get receipt/invoice for a specific order.
     */
    public function receipt(Request $request, Order $order): JsonResponse
    {
        // Verify order belongs to user
        if ($order->user_id !== $request->user()->id) {
            return self::errorResponse('Order not found', null, 404);
        }

        $order->load(['items.meal.category', 'items.meal.subcategory', 'address', 'user']);
        $receipt = $this->paymentService->formatReceipt($order);

        return self::successResponse('Receipt retrieved successfully', $receipt);
    }

    /**
     * Get invoice for a specific order (alias for receipt).
     */
    public function invoice(Request $request, Order $order): JsonResponse
    {
        return $this->receipt($request, $order);
    }
}
