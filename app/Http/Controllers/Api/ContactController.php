<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Contact\SubmitContactMessageAction;
use App\Http\Actions\Api\Contact\UpdateContactStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Contact\ContactIndexRequest;
use App\Http\Requests\Api\Contact\SubmitContactRequest;
use App\Http\Requests\Api\Contact\UpdateContactStatusRequest;
use App\Http\Resources\ContactMessageCollection;
use App\Http\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use App\Traits\ApiResponse;


class ContactController extends Controller
{
    use ApiResponse;

    public function submit(
        SubmitContactRequest $request,
        SubmitContactMessageAction $action
    ) {

        $message = $action->execute(
            $request->validated(),
            $request->ip(),
            $request->userAgent()
        );

        return $this->successResponse(
            'Thank you for your message. We will get back to you soon.',
            new ContactMessageResource(
                $message
            ),
            201
        );
    }

    public function index(
        ContactIndexRequest $request
    ) {

        $this->authorize(
            'viewAny',
            ContactMessage::class
        );

        $query = ContactMessage::query();

        // filters here

        return new ContactMessageCollection(
            $query->paginate(
                $request->per_page ?? 20
            )
        );
    }

    public function show(
        ContactMessage $contactMessage
    ) {

        $this->authorize(
            'view',
            $contactMessage
        );

        if (
            $contactMessage->status
            === 'new'
        ) {
            $contactMessage
                ->markAsRead();
        }

        return new ContactMessageResource(
            $contactMessage
        );
    }

    public function updateStatus(
        UpdateContactStatusRequest $request,
        ContactMessage $contactMessage,
        UpdateContactStatusAction $action
    ) {

        $this->authorize(
            'update',
            $contactMessage
        );

        return $this->successResponse(
            'Status updated successfully',
            new ContactMessageResource(
                $action->execute(
                    $contactMessage,
                    $request->validated()
                )
            )
        );
    }

    public function destroy(
        ContactMessage $contactMessage
    ) {

        $this->authorize(
            'delete',
            $contactMessage
        );

        $contactMessage->delete();

        return $this->successResponse(
            'Message deleted successfully'
        );
    }
}
