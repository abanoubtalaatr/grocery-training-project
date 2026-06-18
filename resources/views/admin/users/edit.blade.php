<x-admin.app-layout title="Edit User">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Edit User</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('admin.users.form', ['submitLabel' => 'Update User'])
    </form>
</x-admin.app-layout>
