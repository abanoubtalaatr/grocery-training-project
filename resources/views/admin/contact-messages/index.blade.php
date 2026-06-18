<x-admin.app-layout title="Contact Messages">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Contact Messages</h1>
    </x-slot>

    @php
        $statusColors = ['new' => 'blue', 'read' => 'slate', 'replied' => 'green', 'spam' => 'red'];
    @endphp

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex w-full flex-col gap-2 sm:flex-row sm:max-w-lg">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search messages..."
                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" />
            <select name="status" onchange="this.form.submit()"
                class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                <option value="">All statuses</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">From</th>
                        <th class="px-6 py-3 font-medium">Subject</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Received</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($messages as $message)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3">
                                <p class="font-medium text-slate-900">{{ $message->name }}</p>
                                <p class="text-xs text-slate-500">{{ $message->email }}</p>
                            </td>
                            <td class="px-6 py-3 max-w-xs truncate text-slate-600">{{ $message->subject ?? '—' }}</td>
                            <td class="px-6 py-3"><x-admin.badge :color="$statusColors[$message->status] ?? 'slate'">{{ ucfirst($message->status) }}</x-admin.badge></td>
                            <td class="px-6 py-3 text-xs text-slate-500">{{ $message->created_at?->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.contact-messages.show', $message) }}" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">View</a>
                                    <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">No messages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $messages->links() }}</div>
</x-admin.app-layout>
