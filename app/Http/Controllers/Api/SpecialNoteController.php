<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\ListSpecialNotesAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SpecialNoteResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class SpecialNoteController extends Controller
{
    use ApiResponseTrait;

    public function index(ListSpecialNotesAction $action): JsonResponse
    {
        return $this->successResponse(SpecialNoteResource::collection($action->execute()));
    }
}
