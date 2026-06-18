<x-admin.app-layout title="New Category">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">New Category</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-3xl">
        @csrf
        @include('admin.categories.form', ['submitLabel' => 'Create Category'])
    </form>
</x-admin.app-layout>
