<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportReportRequest;
use App\Http\Resources\SupportReportResource;
use App\Models\SupportReport;
use Illuminate\Http\JsonResponse;

class SupportController extends Controller
{
    /**
     * Submit a support / problem report for the authenticated user.
     */
    public function store(SupportReportRequest $request): JsonResponse
    {
        $user = $request->user();
        $orderNumber = $request->filled('order_number')
            ? trim((string) $request->input('order_number'))
            : null;

        $report = SupportReport::create([
            'user_id' => $user->id,
            'issue_type' => trim((string) $request->input('issue_type')),
            'order_number' => $orderNumber,
            'message' => trim((string) $request->input('message')),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Support report submitted successfully',
            'data' => new SupportReportResource($report),
        ], 201);
    }
}

