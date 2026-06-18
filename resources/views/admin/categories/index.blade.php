<x-admin.app-layout title="Categories">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Categories</h1>
    </x-slot>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="w-full sm:max-w-xs">
            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search categories..."
                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
            />
        </form>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
            New Category
        </a>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Meals</th>
                        <th class="px-6 py-3 font-medium">Subcategories</th>
                        <th class="px-6 py-3 font-medium">Sort</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $category->image_url }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $category->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $category->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-slate-600">{{ $category->meals_count }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $category->subcategories_count }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $category->sort_order }}</td>
                            <td class="px-6 py-3">
                                <x-admin.badge :color="$category->is_active ? 'green' : 'slate'">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </x-admin.badge>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</x-admin.app-layout>
