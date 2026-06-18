<x-admin.app-layout title="New Subcategory">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">New Subcategory</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.subcategories.store') }}" class="max-w-3xl">
        @csrf
        @include('admin.subcategories.form', ['submitLabel' => 'Create Subcategory'])
    </form>
</x-admin.app-layout>
