<x-admin.app-layout title="Category">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">{{ $category->name }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <x-admin.card>
            <div class="flex items-start gap-4">
                <img src="{{ $category->image_url }}" alt="" class="h-20 w-20 rounded-lg object-cover">
                <div class="space-y-1">
                    <p class="text-lg font-semibold text-slate-900">{{ $category->name }}</p>
                    <p class="text-sm text-slate-500">{{ $category->slug }}</p>
                    <x-admin.badge :color="$category->is_active ? 'green' : 'slate'">{{ $category->is_active ? 'Active' : 'Inactive' }}</x-admin.badge>
                </div>
            </div>
            @if ($category->description)
                <p class="mt-4 text-sm text-slate-600">{{ $category->description }}</p>
            @endif
            <dl class="mt-4 grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">Meals</dt><dd class="font-medium text-slate-900">{{ $category->meals_count }}</dd></div>
                <div><dt class="text-slate-500">Subcategories</dt><dd class="font-medium text-slate-900">{{ $category->subcategories_count }}</dd></div>
            </dl>
        </x-admin.card>

        <div class="flex gap-3">
            <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Edit</a>
            <a href="{{ route('admin.categories.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
        </div>
    </div>
</x-admin.app-layout>
