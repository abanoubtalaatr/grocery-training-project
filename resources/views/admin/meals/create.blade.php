<x-admin.app-layout title="New Meal">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">New Meal</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.meals.store') }}">
        @csrf
        @include('admin.meals.form', ['submitLabel' => 'Create Meal'])
    </form>
</x-admin.app-layout>
