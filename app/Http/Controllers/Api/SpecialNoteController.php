<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SpecialNoteResource;
use App\Models\SpecialNote;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;

class SpecialNoteController extends Controller
{
    use ApiResponseCollection;

    public function index(): JsonResponse
    {
        $specialNotes = SpecialNote::all();

        return self::collectionResponse(
            'Special notes retrieved successfully',
            SpecialNoteResource::collection($specialNotes)
        );
    }
}
