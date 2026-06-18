<x-admin.app-layout title="New Offer">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">New Offer</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.offers.store') }}">
        @csrf
        @include('admin.offers.form', ['submitLabel' => 'Create Offer'])
    </form>
</x-admin.app-layout>
