@extends('layouts.app')

@section('title', __('app.create_category'))

@section('header')
@endsection

@section('heading', __('app.create_category'))

@section('actions')
    <a href="{{ route('admin.categories.index') }}"
        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('app.back_to_list') }}
    </a>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

    <!-- Header -->
    <div class="px-6 py-5 border-b bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('app.create_new_category') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ __('app.fill_category_info') }}</p>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1  gap-6">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    {{ __('app.name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all
                    {{ $errors->has('name') ? 'border-red-400 bg-red-50' : '' }}">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

           
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.description') }}</label>
            <textarea name="description" rows="4"
                class="w-full rounded-3xl border border-gray-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none resize-y transition-all
                {{ $errors->has('description') ? 'border-red-400 bg-red-50' : '' }}">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Image Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.image') }}</label>
            <div class="flex items-start gap-6">
                <div id="image-preview" class="hidden">
                    <img id="preview-img" src="" alt="Preview"
                        class="w-24 h-24 object-cover rounded-2xl border border-gray-200 shadow-sm">
                </div>

                <label class="flex-1 flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-gray-200 bg-gray-50 px-6 py-10 cursor-pointer hover:border-emerald-400 hover:bg-emerald-50 transition-all">
                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M13.5 3.75h6.75M16.875 3.75v6.75M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M3 16.5l4.72-4.72"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-600">{{ __('app.click_to_upload') }}</span>
                    <span class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP — max 2MB</span>
                    <input type="file" name="image" accept="image/*" class="sr-only" onchange="previewImage(this)">
                </label>
            </div>
            @error('image')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.status') }}</label>
                <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                    <div class="relative">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 rounded-full bg-gray-200 peer-checked:bg-emerald-500 transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm text-gray-700">{{ __('app.active') }}</span>
                </label>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('app.sort_order') }}</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all
                    {{ $errors->has('sort_order') ? 'border-red-400 bg-red-50' : '' }}">
                @error('sort_order')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="pt-6 border-t flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('app.save_category') }}
            </button>

            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all">
                {{ __('app.cancel') }}
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const img = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection

