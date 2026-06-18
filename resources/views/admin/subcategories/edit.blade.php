<x-admin.app-layout title="Edit Subcategory">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Edit Subcategory</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.subcategories.update', $subcategory) }}" class="max-w-3xl">
        @csrf
        @method('PUT')
        @include('admin.subcategories.form', ['submitLabel' => 'Update Subcategory'])
    </form>
</x-admin.app-layout>
