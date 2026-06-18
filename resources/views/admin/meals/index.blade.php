<x-admin.app-layout title="Meals">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Meals</h1>
    </x-slot>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex w-full flex-col gap-2 sm:flex-row sm:max-w-lg">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search meals..."
                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" />
            <select name="category_id" onchange="this.form.submit()"
                class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                <option value="">All categories</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}" @selected(request('category_id') == $id)>{{ $name }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.meals.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
            New Meal
        </a>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Meal</th>
                        <th class="px-6 py-3 font-medium">Category</th>
                        <th class="px-6 py-3 font-medium">Price</th>
                        <th class="px-6 py-3 font-medium">Stock</th>
                        <th class="px-6 py-3 font-medium">Flags</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($meals as $meal)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($meal->image_url)
                                        <img src="{{ $meal->image_url }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">N/A</span>
                                    @endif
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $meal->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $meal->brand ?? $meal->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-slate-600">{{ $meal->category?->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-slate-600">
                                <span class="font-medium text-slate-900">${{ number_format((float) $meal->price, 2) }}</span>
                                @if ($meal->getRawDiscountPrice() !== null)
                                    <span class="ml-1 text-xs text-emerald-600">${{ number_format((float) $meal->getRawDiscountPrice(), 2) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <x-admin.badge :color="$meal->stock_quantity > 0 ? 'green' : 'red'">{{ $meal->stock_quantity }}</x-admin.badge>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @if ($meal->is_featured)<x-admin.badge color="amber">Featured</x-admin.badge>@endif
                                    @if ($meal->is_hot)<x-admin.badge color="red">Hot</x-admin.badge>@endif
                                    @unless ($meal->is_available)<x-admin.badge color="slate">Hidden</x-admin.badge>@endunless
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.meals.edit', $meal) }}" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Edit</a>
                                    <form method="POST" action="{{ route('admin.meals.destroy', $meal) }}" onsubmit="return confirm('Delete this meal?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">No meals found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $meals->links() }}</div>
</x-admin.app-layout>
