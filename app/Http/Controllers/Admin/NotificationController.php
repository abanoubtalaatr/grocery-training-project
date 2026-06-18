<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = Notification::query()
            ->when($request->string('status')->toString() === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->when($request->string('status')->toString() === 'read', fn ($query) => $query->whereNotNull('read_at'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function show(Notification $notification): View
    {
        return view('admin.notifications.show', compact('notification'));
    }

    public function destroy(Notification $notification): RedirectResponse
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully.');
    }
}
