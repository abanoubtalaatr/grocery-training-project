<x-admin.app-layout title="New User">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">New User</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users.form', ['submitLabel' => 'Create User'])
    </form>
</x-admin.app-layout>
