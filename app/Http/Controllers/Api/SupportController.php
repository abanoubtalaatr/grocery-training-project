<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SupportReportResource;
use App\Models\Order;
use App\Models\SupportReport;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    use ApiResponse;

    /**
     * Submit a support / problem report for the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'issue_type' => ['required', 'string', 'min:2', 'max:255'],
            'order_number' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return self::errorResponse('Validation failed', $validator->errors(), 422);
        }

        $user = $request->user();
        $orderNumber = $request->filled('order_number')
            ? trim((string) $request->input('order_number'))
            : null;

        if ($orderNumber !== null && $orderNumber !== '') {
            $orderExists = Order::query()
                ->where('user_id', $user->id)
                ->where('order_number', $orderNumber)
                ->exists();

            if (! $orderExists) {
                return self::errorResponse('Validation failed', [
                    'order_number' => ['Order number not found on your account.'],
                ], 422);
            }
        } else {
            $orderNumber = null;
        }

        $report = SupportReport::create([
            'user_id' => $user->id,
            'issue_type' => trim((string) $request->input('issue_type')),
            'order_number' => $orderNumber,
            'message' => trim((string) $request->input('message')),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return self::successResponse(
            'Support report submitted successfully',
            new SupportReportResource($report),
            201
        );
    }
}
