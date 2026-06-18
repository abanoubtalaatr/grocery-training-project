<x-admin.app-layout title="New FAQ">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">New FAQ</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.faqs.store') }}">
        @csrf
        @include('admin.faqs.form', ['submitLabel' => 'Create FAQ'])
    </form>
</x-admin.app-layout>
