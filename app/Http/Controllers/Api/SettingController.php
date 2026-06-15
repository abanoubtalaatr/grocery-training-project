<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\GetPublicSettingsAction;
use App\Actions\Api\GetSettingsAction;
use App\Actions\Api\UpdateSettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get settings
     */
    public function index(GetSettingsAction $action): JsonResponse
    {
        return $this->successResponse(new SettingResource($action->execute()));
    }

    /**
     * Update settings
     */
    public function update(SettingRequest $request, UpdateSettingsAction $action): JsonResponse
    {
        return $this->successResponse(
            new SettingResource($action->execute($request)),
            'Settings updated successfully',
        );
    }

    /**
     * Get specific settings for public use
     */
    public function publicSettings(GetPublicSettingsAction $action): JsonResponse
    {
        return $this->jsonResponse($action->execute());
    }
}
