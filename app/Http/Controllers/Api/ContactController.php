<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use App\Services\ContactService;
use App\Traits\V1\ApiResponse;
use App\Traits\V1\ApiResponseCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use ApiResponse, ApiResponseCollection;

    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Submit a contact message.
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $contactMessage = $this->contactService->submitMessage(
            $request->validated(),
            $request->ip(),
            $request->userAgent()
        );

        return self::successResponse(
            'Thank you for your message. We will get back to you soon.',
            new ContactMessageResource($contactMessage),
            201
        );
    }

    /**
     * Get all contact messages (admin only).
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ContactMessage::class);

        $messages = $this->contactService->getMessages($request->all(), $request->get('per_page', 20));

        return self::collectionResponse(
            'Contact messages retrieved successfully',
            ContactMessageResource::collection($messages)
        );
    }

    /**
     * Show specific contact message (admin only).
     */
    public function show(ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('view', $contactMessage);

        if ($contactMessage->status === 'new') {
            $this->contactService->updateMessageStatus($contactMessage, 'read');
        }

        return self::successResponse(
            'Contact message retrieved successfully',
            new ContactMessageResource($contactMessage)
        );
    }

    /**
     * Update contact message status (admin only).
     */
    public function update(Request $request, ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('update', $contactMessage);

        $validated = $request->validate([
            'status' => 'required|in:read,replied,spam',
            'admin_notes' => 'nullable|string',
        ]);

        $this->contactService->updateMessageStatus(
            $contactMessage,
            $validated['status'],
            $validated['admin_notes'] ?? null
        );

        return self::successResponse(
            'Status updated successfully',
            new ContactMessageResource($contactMessage)
        );
    }

    /**
     * Delete contact message (admin only).
     */
    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('delete', $contactMessage);

        $contactMessage->delete();

        return self::successResponse('Message deleted successfully');
    }
}
