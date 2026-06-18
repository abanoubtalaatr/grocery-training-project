<x-admin.app-layout title="Edit FAQ">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Edit FAQ</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
        @csrf
        @method('PUT')
        @include('admin.faqs.form', ['submitLabel' => 'Update FAQ'])
    </form>
</x-admin.app-layout>
