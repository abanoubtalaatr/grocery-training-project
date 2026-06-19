@extends('layouts.app')

@section('title', $category->name)

@section('header')
@endsection

@section('heading', $category->name)

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.categories.edit', $category) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ __('app.edit') }}
        </a>

        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
            onsubmit="return confirm('{{ __('app.delete_confirm_category') }}')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 shadow-sm hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                {{ __('app.delete') }}
            </button>
        </form>

        <a href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back_to_list') }}
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- Left: Image & status --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="p-6 flex flex-col items-center text-center">
            @if ($category->image)
                <img src="{{ Storage::url($category->image) }}"
                    alt="{{ $category->name }}"
                    class="w-full max-h-64 rounded-xl object-cover border border-gray-100 shadow-sm mb-5"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($category->name) }}&size=256&background=e5e7eb&color=6b7280&bold=true'">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($category->name) }}&size=256&background=e5e7eb&color=6b7280&bold=true"
                    alt="{{ $category->name }}"
                    class="w-full max-h-64 rounded-xl object-cover border border-gray-100 shadow-sm mb-5">
            @endif

            <h3 class="text-lg font-bold text-gray-900">{{ $category->name }}</h3>
            <code class="mt-1 text-xs text-gray-500 bg-gray-100 rounded px-2 py-0.5">{{ $category->slug }}</code>

            <div class="mt-4">
                @if ($category->is_active)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-sm font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        {{ __('app.active') }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                        {{ __('app.inactive') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Info Card --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-800">{{ __('app.category_information') }}</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">{{ __('app.sort_order') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $category->sort_order ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">{{ __('app.created_at') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $category->created_at->format('d M, Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $category->created_at->format('H:i') }}</p>
                    </div>
                </div>

                @if ($category->description)
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">{{ __('app.description') }}</p>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $category->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-emerald-600">{{ $category->meals_count ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ __('app.meals') }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $category->subcategories_count ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ __('app.subcategories') }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-5 text-center">
                <p class="text-2xl font-bold {{ $category->is_active ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $category->is_active ? __('app.yes') : __('app.no') }}
                </p>
                <p class="mt-1 text-xs text-gray-500">{{ __('app.active_status') }}</p>
            </div>
        </div>

    </div>
</div>
@endsection