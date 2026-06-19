@extends('layouts.app')

@section('title', __('app.edit_category'))
@section('page-title', __('app.edit_category'))

@section('header')
@endsection

@section('heading', __('app.edit_category'))

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.categories.show', $category) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ __('app.view') }}
        </a>
        <a href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('app.back_to_list') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-base font-semibold text-gray-800">{{ $category->name }}</h2>
            <p class="mt-0.5 text-sm text-gray-500">{{ __('app.update_category_info') }}</p>
        </div>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data"
            class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        {{ __('app.name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition
                        {{ $errors->has('name') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.slug') }}</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition
                        {{ $errors->has('slug') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}">
                    @error('slug')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.description') }}</label>
                <textarea name="description" rows="4"
                    class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition resize-none
                    {{ $errors->has('description') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.image') }}</label>
                <div class="flex items-start gap-4">
                    {{-- Current Image - fixed size --}}
                    <div id="image-preview" class="flex-shrink-0">
                        @if ($category->image && Storage::disk('public')->exists($category->image))
                            <img id="preview-img" src="{{ Storage::disk('public')->url($category->image) }}" alt="{{ $category->name }}"
                                class="w-28 h-28 rounded-xl object-cover border border-gray-200 shadow-sm"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($category->name) }}&size=112&background=e5e7eb&color=6b7280&bold=true'">
                        @else
                            <img id="preview-img" src="https://ui-avatars.com/api/?name={{ urlencode($category->name) }}&size=112&background=e5e7eb&color=6b7280&bold=true"
                                alt="{{ $category->name }}"
                                class="w-28 h-28 rounded-xl object-cover border border-gray-200 shadow-sm">
                        @endif
                    </div>
                    <label
                        class="flex-1 flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 px-4 py-5 text-center cursor-pointer hover:border-emerald-400 hover:bg-emerald-50 transition">
                        <svg class="w-7 h-7 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M13.5 3.75h6.75M16.875 3.75v6.75M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M3 16.5l4.72-4.72" />
                        </svg>
                        <span class="text-sm text-gray-500">{{ __('app.click_to_change') }}</span>
                        <span class="mt-1 text-xs text-gray-400">PNG, JPG, WEBP — max 2MB</span>
                        <input type="file" name="image" accept="image/*" class="sr-only" onchange="previewImage(this)">
                    </label>
                </div>
                @error('image')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.status') }}</label>
                    <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                        <div class="relative">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="sr-only peer">
                            <div
                                class="w-10 h-5 rounded-full bg-gray-200 peer-checked:bg-emerald-500 transition-colors duration-200">
                            </div>
                            <div
                                class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200 peer-checked:translate-x-5">
                            </div>
                        </div>
                        <span class="text-sm text-gray-600">{{ __('app.active') }}</span>
                    </label>
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.sort_order') }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                        min="0"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition
                        {{ $errors->has('sort_order') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}">
                    @error('sort_order')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="mt-7 flex items-center gap-3 border-t border-gray-100 pt-5">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('app.update_category') }}
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 transition">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function previewImage(input) {
                const img = document.getElementById('preview-img');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection