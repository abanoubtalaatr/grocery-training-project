<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SmartListRequest;
use App\Http\Resources\Api\SmartListResource;
use App\Models\SmartList;
use App\Services\SmartListService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmartListController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $smartListService;

    public function __construct(SmartListService $smartListService)
    {
        $this->smartListService = $smartListService;
    }

    public function index(Request $request): JsonResponse
    {
        $smartLists = $this->smartListService->getSmartLists($request->user());
        return self::collectionResponse(
            'Smart lists retrieved successfully',
            SmartListResource::collection($smartLists)
        );
    }

    public function store(SmartListRequest $request): JsonResponse
    {
        $smartList = $this->smartListService->createSmartList($request->user(), $request->validated());

        return self::successResponse(
            'Wish list created successfully',
            new SmartListResource($smartList),
            201
        );
    }

    public function show(Request $request, SmartList $smartList): JsonResponse
    {
        // Check ownership if not handled by policy
        if ($smartList->user_id !== $request->user()->id) {
            return self::errorResponse('Smart list not found', null, 404);
        }

        return self::successResponse(
            'Smart list retrieved successfully',
            new SmartListResource($smartList->load('meals'))
        );
    }

    public function update(SmartListRequest $request, SmartList $smartList): JsonResponse
    {
        if ($smartList->user_id !== $request->user()->id) {
            return self::errorResponse('Unauthorized', null, 403);
        }

        $updatedSmartList = $this->smartListService->updateSmartList($smartList, $request->validated());

        return self::successResponse(
            'Wish list updated successfully',
            new SmartListResource($updatedSmartList)
        );
    }

    public function destroy(Request $request, SmartList $smartList): JsonResponse
    {
        if ($smartList->user_id !== $request->user()->id) {
            return self::errorResponse('Unauthorized', null, 403);
        }

        $this->smartListService->deleteSmartList($smartList);

        return self::successResponse('Wish list deleted successfully');
    }
}
