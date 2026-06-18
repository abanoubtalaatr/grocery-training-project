<x-admin.app-layout title="Notifications">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Notifications</h1>
    </x-slot>

    <div class="mb-4 flex justify-end">
        <form method="GET" class="w-full sm:max-w-xs">
            <select name="status" onchange="this.form.submit()"
                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                <option value="">All</option>
                <option value="unread" @selected(request('status') === 'unread')>Unread</option>
                <option value="read" @selected(request('status') === 'read')>Read</option>
            </select>
        </form>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Type</th>
                        <th class="px-6 py-3 font-medium">Title / Message</th>
                        <th class="px-6 py-3 font-medium">State</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($notifications as $notification)
                        @php $data = $notification->data ?? []; @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 text-xs text-slate-500">{{ class_basename($notification->type) }}</td>
                            <td class="px-6 py-3 max-w-md truncate text-slate-700">{{ $data['title'] ?? ($data['message'] ?? '—') }}</td>
                            <td class="px-6 py-3"><x-admin.badge :color="$notification->read_at ? 'slate' : 'blue'">{{ $notification->read_at ? 'Read' : 'Unread' }}</x-admin.badge></td>
                            <td class="px-6 py-3 text-xs text-slate-500">{{ $notification->created_at?->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.notifications.show', $notification->id) }}" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">View</a>
                                    <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" onsubmit="return confirm('Delete this notification?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">No notifications found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $notifications->links() }}</div>
</x-admin.app-layout>
