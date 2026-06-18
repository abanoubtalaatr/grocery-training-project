<x-admin.app-layout title="FAQ">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">FAQ</h1>
    </x-slot>

    <div class="max-w-2xl space-y-6">
        <x-admin.card>
            <p class="font-semibold text-slate-900">{{ $faq->question }}</p>
            <p class="mt-3 text-sm text-slate-700">{{ $faq->answer }}</p>
            <div class="mt-4 flex items-center gap-3 text-xs text-slate-500">
                <span>{{ $faq->category ?? 'Uncategorized' }}</span>
                <x-admin.badge :color="$faq->is_active ? 'green' : 'slate'">{{ $faq->is_active ? 'Active' : 'Inactive' }}</x-admin.badge>
            </div>
        </x-admin.card>

        <div class="flex gap-3">
            <a href="{{ route('admin.faqs.edit', $faq) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Edit</a>
            <a href="{{ route('admin.faqs.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
        </div>
    </div>
</x-admin.app-layout>
