<x-admin.app-layout title="Edit Meal">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Edit Meal</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.meals.update', $meal) }}">
        @csrf
        @method('PUT')
        @include('admin.meals.form', ['submitLabel' => 'Update Meal'])
    </form>
</x-admin.app-layout>
