<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpecialNoteResource;
use App\Models\SpecialNote;
use Illuminate\Http\JsonResponse;

class SpecialNoteController extends Controller
{
    /**
     * Get all special notes.
     */
    public function index(): JsonResponse
    {
        $specialNotes = SpecialNote::all();
        
        return response()->json([
            'success' => true,
            'data' => SpecialNoteResource::collection($specialNotes)
        ]);
    }
}

