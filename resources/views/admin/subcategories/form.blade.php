<x-admin.card>
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <x-admin.select name="category_id" label="Category" :options="$categories" :selected="$subcategory->category_id" placeholder="Select a category" required />

        <x-admin.input name="name" label="Name" :value="$subcategory->name" required />

        <div class="md:col-span-2">
            <x-admin.textarea name="description" label="Description" :value="$subcategory->description" />
        </div>

        <div class="md:col-span-2">
            <x-admin.input name="image_url" label="Image URL" :value="$subcategory->getRawOriginal('image_url')" hint="Paste an image URL or storage path." />
        </div>

        <x-admin.input name="order" label="Order" type="number" :value="$subcategory->order ?? 0" />

        <div class="flex items-end">
            <x-admin.checkbox name="is_active" label="Active" :checked="$subcategory->is_active ?? true" />
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3 border-t border-slate-200 pt-5">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $submitLabel }}</button>
        <a href="{{ route('admin.subcategories.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Cancel</a>
    </div>
</x-admin.card>
