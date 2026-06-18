<x-admin.app-layout title="Edit Offer">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Edit Offer</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.offers.update', $offer) }}">
        @csrf
        @method('PUT')
        @include('admin.offers.form', ['submitLabel' => 'Update Offer'])
    </form>
</x-admin.app-layout>
