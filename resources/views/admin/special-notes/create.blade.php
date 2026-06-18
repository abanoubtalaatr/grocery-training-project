<x-admin.app-layout title="New Special Note">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">New Special Note</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.special-notes.store') }}">
        @csrf
        @include('admin.special-notes.form', ['submitLabel' => 'Create Note'])
    </form>
</x-admin.app-layout>
