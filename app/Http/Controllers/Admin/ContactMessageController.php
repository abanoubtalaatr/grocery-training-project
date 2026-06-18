<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactMessageStatusRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contact-messages.index', [
            'messages' => $messages,
            'statuses' => $this->statuses(),
        ]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        if ($contactMessage->status === 'new') {
            $contactMessage->markAsRead();
        }

        return view('admin.contact-messages.show', [
            'message' => $contactMessage,
            'statuses' => $this->statuses(),
        ]);
    }

    public function updateStatus(ContactMessageStatusRequest $request, ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update($request->validated());

        return back()->with('success', 'Message updated.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted successfully.');
    }

    /**
     * @return array<string, string>
     */
    private function statuses(): array
    {
        return [
            'new' => 'New',
            'read' => 'Read',
            'replied' => 'Replied',
            'spam' => 'Spam',
        ];
    }
}
