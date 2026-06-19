<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\ContactMessageService;

class ContactMessageController extends Controller
{
    public function __construct(
        private ContactMessageService $contactMessageService
    ) {}

    public function index(Request $request)
    {
        $messages = $this->contactMessageService
            ->paginate($request);

        return view(
            'admin.contact-messages.index',
            compact('messages')
        );
    }

    public function show(
        ContactMessage $contactMessage
    ) {
        return view(
            'admin.contact-messages.show',
            compact('contactMessage')
        );
    }

    public function markAsRead(
        ContactMessage $contactMessage
    ) {
        $this->contactMessageService
            ->markAsRead($contactMessage);

        return back();
    }

    public function markAsReplied(
        ContactMessage $contactMessage
    ) {
        $this->contactMessageService
            ->markAsReplied($contactMessage);

        return back();
    }

    public function markAsSpam(
        ContactMessage $contactMessage
    ) {
        $this->contactMessageService
            ->markAsSpam($contactMessage);

        return back();
    }

    public function destroy(
        ContactMessage $contactMessage
    ) {
        $this->contactMessageService
            ->delete($contactMessage);

        return back();
    }
}