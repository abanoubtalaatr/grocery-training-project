@php
    $keywords = old('meta_keywords', $page->meta_keywords ?? []);
    $keywordsValue = is_array($keywords) ? implode(', ', $keywords) : $keywords;
@endphp

<div class="max-w-3xl space-y-6">
    <x-admin.card>
        <h2 class="mb-4 text-base font-semibold text-slate-900">Content</h2>
        <div class="space-y-5">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <x-admin.input name="title" label="Title" :value="$page->title" required />
                <x-admin.input name="slug" label="Slug" :value="$page->slug" hint="Auto-generated from title if left blank." />
            </div>
            <x-admin.textarea name="content" label="Content" :value="$page->content" rows="10" />
        </div>
    </x-admin.card>

    <x-admin.card>
        <h2 class="mb-4 text-base font-semibold text-slate-900">SEO & Visibility</h2>
        <div class="space-y-5">
            <x-admin.input name="meta_title" label="Meta Title" :value="$page->meta_title" />
            <x-admin.textarea name="meta_description" label="Meta Description" :value="$page->meta_description" rows="2" />

            <div>
                <label for="meta_keywords" class="mb-1 block text-sm font-medium text-slate-700">Meta Keywords</label>
                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ $keywordsValue }}" placeholder="grocery, delivery, fresh"
                    class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" />
                <p class="mt-1 text-xs text-slate-500">Comma-separated list.</p>
                @error('meta_keywords')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <x-admin.input name="order" label="Order" type="number" :value="$page->order ?? 0" />
                <div class="flex items-end">
                    <x-admin.checkbox name="is_published" label="Published" :checked="$page->is_published ?? false" />
                </div>
            </div>
        </div>
    </x-admin.card>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $submitLabel }}</button>
        <a href="{{ route('admin.static-pages.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Cancel</a>
    </div>
</div>
