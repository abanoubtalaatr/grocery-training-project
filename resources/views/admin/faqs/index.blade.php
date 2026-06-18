<x-admin.app-layout title="FAQs">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">FAQs</h1>
    </x-slot>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="w-full sm:max-w-xs">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search FAQs..."
                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" />
        </form>
        <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
            New FAQ
        </a>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Question</th>
                        <th class="px-6 py-3 font-medium">Category</th>
                        <th class="px-6 py-3 font-medium">Order</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($faqs as $faq)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 max-w-md truncate font-medium text-slate-900">{{ $faq->question }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $faq->category ?? '—' }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $faq->order }}</td>
                            <td class="px-6 py-3"><x-admin.badge :color="$faq->is_active ? 'green' : 'slate'">{{ $faq->is_active ? 'Active' : 'Inactive' }}</x-admin.badge></td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Edit</a>
                                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">No FAQs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $faqs->links() }}</div>
</x-admin.app-layout>
