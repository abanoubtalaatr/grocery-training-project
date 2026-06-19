@extends('layouts.app')

@section('title', __('app.edit_subcategory'))
@section('page-title', __('app.edit_subcategory'))

@section('header')
@endsection

@section('heading', __('app.edit_subcategory'))

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.subcategories.show', $subcategory) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ __('app.view') }}
        </a>
        <a href="{{ route('admin.subcategories.index') }}"
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
            <h2 class="text-base font-semibold text-gray-800">{{ $subcategory->name }}</h2>
            <p class="mt-0.5 text-sm text-gray-500">{{ __('app.update_subcategory_info') }}</p>
        </div>

        <form action="{{ route('admin.subcategories.update', $subcategory) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- Parent Category --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        {{ __('app.parent_category') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition bg-white
                        {{ $errors->has('category_id') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}"
                        required>
                        <option value="">{{ __('app.select_category') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $subcategory->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        {{ __('app.name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $subcategory->name) }}" required
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition
                        {{ $errors->has('name') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.slug') }}</label>
                    <input type="text" name="slug" value="{{ old('slug', $subcategory->slug) }}"
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
                <textarea name="description" rows="3"
                    class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition resize-none
                    {{ $errors->has('description') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}">{{ old('description', $subcategory->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image URL --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.image_url') }}</label>
                <div class="flex items-start gap-4">
                    <div id="image-preview"
                        class="flex-shrink-0 {{ old('image_url', $subcategory->image_url) ? '' : 'hidden' }}">
                        <img id="preview-img" src="{{ old('image_url', $subcategory->image_url) }}"
                            alt="{{ $subcategory->name }}"
                            class="w-20 h-20 rounded-xl object-cover border border-gray-200 shadow-sm"
                            onerror="this.closest('#image-preview').classList.add('hidden')">
                    </div>
                    <div class="flex-1">
                        <input type="url" name="image_url" value="{{ old('image_url', $subcategory->image_url) }}"
                            placeholder="https://example.com/image.jpg" oninput="previewImageUrl(this.value)"
                            class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition
                            {{ $errors->has('image_url') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}">
                        <p class="mt-1.5 text-xs text-gray-400">{{ __('app.image_url_hint') }}</p>
                    </div>
                </div>
                @error('image_url')
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
                                {{ old('is_active', $subcategory->is_active) ? 'checked' : '' }} class="sr-only peer">
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
                    <input type="number" name="order" value="{{ old('order', $subcategory->order) }}" min="0"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition
                        {{ $errors->has('order') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-gray-200 bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100' }}">
                    @error('order')
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
                    {{ __('app.update_subcategory') }}
                </button>
                <a href="{{ route('admin.subcategories.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 transition">
                    {{ __('app.cancel') }}
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function previewImageUrl(url) {
                const preview = document.getElementById('image-preview');
                const img = document.getElementById('preview-img');
                if (url) {
                    img.src = url;
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
            }
        </script>
    @endpush
@endsection
