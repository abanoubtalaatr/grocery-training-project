<x-admin.app-layout title="Edit Special Note">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Edit Special Note</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.special-notes.update', $specialNote) }}">
        @csrf
        @method('PUT')
        @include('admin.special-notes.form', ['submitLabel' => 'Update Note'])
    </form>
</x-admin.app-layout>
