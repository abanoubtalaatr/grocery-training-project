<x-admin.app-layout title="Subcategory">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">{{ $subcategory->name }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <x-admin.card>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">Name</dt><dd class="font-medium text-slate-900">{{ $subcategory->name }}</dd></div>
                <div><dt class="text-slate-500">Category</dt><dd class="font-medium text-slate-900">{{ $subcategory->category?->name ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Slug</dt><dd class="font-medium text-slate-900">{{ $subcategory->slug }}</dd></div>
                <div><dt class="text-slate-500">Meals</dt><dd class="font-medium text-slate-900">{{ $subcategory->meals_count }}</dd></div>
                <div><dt class="text-slate-500">Status</dt><dd><x-admin.badge :color="$subcategory->is_active ? 'green' : 'slate'">{{ $subcategory->is_active ? 'Active' : 'Inactive' }}</x-admin.badge></dd></div>
            </dl>
            @if ($subcategory->description)
                <p class="mt-4 text-sm text-slate-600">{{ $subcategory->description }}</p>
            @endif
        </x-admin.card>

        <div class="flex gap-3">
            <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Edit</a>
            <a href="{{ route('admin.subcategories.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
        </div>
    </div>
</x-admin.app-layout>
