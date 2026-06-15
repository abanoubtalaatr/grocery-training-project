<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\ContactService;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class ContactStatsController extends Controller
{
    use ApiResponse;

    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Get contact statistics (admin only).
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', ContactMessage::class);

        $stats = $this->contactService->getStatistics();

        return self::successResponse('Contact statistics retrieved successfully', $stats);
    }
}
