<x-admin.app-layout title="Edit Category">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Edit Category</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="max-w-3xl">
        @csrf
        @method('PUT')
        @include('admin.categories.form', ['submitLabel' => 'Update Category'])
    </form>
</x-admin.app-layout>
