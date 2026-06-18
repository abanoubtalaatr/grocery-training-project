<x-admin.app-layout title="Page">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">{{ $page->title }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <x-admin.card>
            <div class="mb-3 flex items-center gap-3">
                <code class="rounded bg-slate-100 px-2 py-1 text-xs">{{ $page->slug }}</code>
                <x-admin.badge :color="$page->is_published ? 'green' : 'slate'">{{ $page->is_published ? 'Published' : 'Draft' }}</x-admin.badge>
            </div>
            <div class="prose prose-sm max-w-none text-slate-700">{!! nl2br(e($page->content)) !!}</div>
        </x-admin.card>

        <div class="flex gap-3">
            <a href="{{ route('admin.static-pages.edit', $page) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Edit</a>
            <a href="{{ route('admin.static-pages.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
        </div>
    </div>
</x-admin.app-layout>
