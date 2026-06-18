<x-admin.app-layout title="New Page">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">New Page</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.static-pages.store') }}">
        @csrf
        @include('admin.static-pages.form', ['submitLabel' => 'Create Page'])
    </form>
</x-admin.app-layout>
