<?php
namespace App\Http\Controllers\Api;

use App\Actions\Contact\{SubmitContactAction, GetContactMessagesAction, GetContactStatisticsAction};
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{SubmitContactRequest, UpdateContactStatusRequest};
use App\Http\Resources\{ContactMessageCollection, ContactMessageResource};
use App\Models\ContactMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use ApiResponse;

    public function submit(SubmitContactRequest $request, SubmitContactAction $action): JsonResponse
    {
        return $this->successResponse(new ContactMessageResource($action->execute($request->Payload())), 'Message sent.', 201);
    }

    public function index(Request $request, GetContactMessagesAction $action): ContactMessageCollection
    {
        $this->authorize('viewAny', ContactMessage::class);
        return new ContactMessageCollection($action->execute($request->all()));
    }

    public function show(ContactMessage $contactMessage): ContactMessageResource
    {
        $this->authorize('view', $contactMessage);
        return new ContactMessageResource($contactMessage->status === 'new' ? $contactMessage->markAsRead() : $contactMessage);
    }

    public function updateStatus(UpdateContactStatusRequest $request, ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('update', $contactMessage);
        return $this->successResponse(new ContactMessageResource(tap($contactMessage)->update($request->validated())), 'Status updated.');
    }

    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $this->authorize('delete', $contactMessage);
        return $this->successResponse($contactMessage->delete(), 'Message deleted.');
    }

    public function statistics(GetContactStatisticsAction $action): JsonResponse
    {
        $this->authorize('viewAny', ContactMessage::class);
        return $this->successResponse($action->execute(), 'Statistics retrieved.');
    }
}