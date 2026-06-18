<x-admin.app-layout title="Edit Page">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Edit Page</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.static-pages.update', $page) }}">
        @csrf
        @method('PUT')
        @include('admin.static-pages.form', ['submitLabel' => 'Update Page'])
    </form>
</x-admin.app-layout>
