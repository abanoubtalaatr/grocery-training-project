<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Payment\GetPaymentHistoryAction;
use App\Http\Actions\Api\Payment\GetReceiptAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PaymentHistoryResource;
use App\Http\Resources\Api\ReceiptResource;
use App\Models\Order;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;

    public function paymentHistory(
        Request $request,
        GetPaymentHistoryAction $action
    ): JsonResponse {

        $payments = $action->execute(
            $request->user()
        );

        return $this->successResponse(
            'Payment history retrieved successfully',
            [
                'items' => PaymentHistoryResource::collection(
                    $payments
                ),

                'total_count' =>
                    $payments->count(),

                'total_amount' =>
                    (float) $payments->sum('total'),
            ]
        );
    }

    public function receipt(
        Request $request,
        Order $order,
        GetReceiptAction $action
    ): JsonResponse {

        return $this->successResponse(
            'Receipt retrieved successfully',
            new ReceiptResource(
                $action->execute(
                    $request->user(),
                    $order
                )
            )
        );
    }

    public function invoice(
        Request $request,
        Order $order,
        GetReceiptAction $action
    ): JsonResponse {

        return $this->receipt(
            $request,
            $order,
            $action
        );
    }
}