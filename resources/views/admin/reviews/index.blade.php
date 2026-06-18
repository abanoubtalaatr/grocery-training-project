<x-admin.app-layout title="Reviews">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Reviews</h1>
    </x-slot>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="w-full sm:max-w-xs">
            <select name="status" onchange="this.form.submit()"
                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                <option value="">All reviews</option>
                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            </select>
        </form>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Meal</th>
                        <th class="px-6 py-3 font-medium">User</th>
                        <th class="px-6 py-3 font-medium">Rating</th>
                        <th class="px-6 py-3 font-medium">Comment</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-medium text-slate-900">{{ $review->meal?->title ?? '—' }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $review->user?->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-amber-500">{{ str_repeat('★', (int) $review->rating) }}<span class="text-slate-300">{{ str_repeat('★', 5 - (int) $review->rating) }}</span></td>
                            <td class="px-6 py-3 max-w-xs truncate text-slate-600">{{ $review->comment }}</td>
                            <td class="px-6 py-3"><x-admin.badge :color="$review->is_approved ? 'green' : 'amber'">{{ $review->is_approved ? 'Approved' : 'Pending' }}</x-admin.badge></td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.reviews.toggle-approval', $review) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                                            {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">No reviews found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $reviews->links() }}</div>
</x-admin.app-layout>
