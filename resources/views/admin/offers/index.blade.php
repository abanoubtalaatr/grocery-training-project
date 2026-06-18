<x-admin.app-layout title="Offers">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Offers</h1>
    </x-slot>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="w-full sm:max-w-xs">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search offers..."
                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" />
        </form>
        <a href="{{ route('admin.offers.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
            New Offer
        </a>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Title</th>
                        <th class="px-6 py-3 font-medium">Code</th>
                        <th class="px-6 py-3 font-medium">Discount</th>
                        <th class="px-6 py-3 font-medium">Validity</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($offers as $offer)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-medium text-slate-900">{{ $offer->title }}</td>
                            <td class="px-6 py-3"><code class="rounded bg-slate-100 px-2 py-1 text-xs">{{ $offer->code }}</code></td>
                            <td class="px-6 py-3 text-slate-600">
                                {{ $offer->type === 'percentage' ? $offer->discount_value . '%' : '$' . number_format((float) $offer->discount_value, 2) }}
                            </td>
                            <td class="px-6 py-3 text-xs text-slate-500">
                                {{ optional($offer->start_date)->format('M d, Y') }} — {{ optional($offer->end_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-3">
                                <x-admin.badge :color="$offer->is_active ? 'green' : 'slate'">{{ $offer->is_active ? 'Active' : 'Inactive' }}</x-admin.badge>
                                @if ($offer->is_featured)<x-admin.badge color="amber">Featured</x-admin.badge>@endif
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.offers.edit', $offer) }}" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Edit</a>
                                    <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" onsubmit="return confirm('Delete this offer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">No offers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $offers->links() }}</div>
</x-admin.app-layout>
