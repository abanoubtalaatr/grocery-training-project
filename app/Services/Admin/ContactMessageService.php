<?php

namespace App\Services\Admin;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageService
{
    public function paginate(
        Request $request,
        int $perPage = 10
    )
    {
        return ContactMessage::query()
            ->filter($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function markAsRead(
        ContactMessage $message
    ): void {
        $message->markAsRead();
    }

    public function markAsReplied(
        ContactMessage $message
    ): void {
        $message->markAsReplied();
    }

    public function markAsSpam(
        ContactMessage $message
    ): void {
        $message->markAsSpam();
    }

    public function delete(
        ContactMessage $message
    ): bool {
        return $message->delete();
    }
}