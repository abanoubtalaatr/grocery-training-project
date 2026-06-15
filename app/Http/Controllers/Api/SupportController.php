<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\CreateSupportReportAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSupportReportRequest;
use App\Http\Resources\Api\SupportReportResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class SupportController extends Controller
{
    use ApiResponseTrait;

    /**
     * Submit a support / problem report for the authenticated user.
     */
    public function store(StoreSupportReportRequest $request, CreateSupportReportAction $action): JsonResponse
    {
        return $this->successResponse(
            new SupportReportResource($action->execute(
                $request->user(),
                $request->supportData(),
                $request->ip(),
                $request->userAgent(),
            )),
            'Support report submitted successfully',
            201,
        );
    }
}
