<div class="max-w-4xl space-y-6">
    <x-admin.card>
        <h2 class="mb-4 text-base font-semibold text-slate-900">Basic Information</h2>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-admin.input name="title" label="Title" :value="$meal->title" required />
            <x-admin.input name="brand" label="Brand" :value="$meal->brand" />

            <x-admin.select name="category_id" label="Category" :options="$categories" :selected="$meal->category_id" placeholder="Select a category" required />
            <x-admin.select name="subcategory_id" label="Subcategory" :options="$subcategories" :selected="$meal->subcategory_id" placeholder="None" />

            <div class="md:col-span-2">
                <x-admin.textarea name="description" label="Description" :value="$meal->description" />
            </div>

            <div class="md:col-span-2">
                <x-admin.input name="image" label="Image URL" :value="$meal->image" hint="Paste an image URL or storage path." />
            </div>
        </div>
    </x-admin.card>

    <x-admin.card>
        <h2 class="mb-4 text-base font-semibold text-slate-900">Pricing & Stock</h2>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-admin.input name="price" label="Price" type="number" step="0.01" :value="$meal->price" required />
            <x-admin.input name="discount_price" label="Discount Price" type="number" step="0.01" :value="$meal->getRawDiscountPrice()" hint="Must be less than or equal to price." />
            <x-admin.input name="offer_title" label="Offer Title" :value="$meal->offer_title" hint="e.g. 20% OFF" />
            <x-admin.input name="size" label="Size" :value="$meal->size" />
            <x-admin.input name="stock_quantity" label="Stock Quantity" type="number" :value="$meal->stock_quantity ?? 0" required />
        </div>
    </x-admin.card>

    <x-admin.card>
        <h2 class="mb-4 text-base font-semibold text-slate-900">Availability</h2>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-admin.input name="available_date" label="Available Date" type="date" :value="optional($meal->available_date)->format('Y-m-d')" />
            <x-admin.input name="expiry_date" label="Expiry Date" type="date" :value="optional($meal->expiry_date)->format('Y-m-d')" />
        </div>
        <div class="mt-5 flex flex-wrap gap-6">
            <x-admin.checkbox name="is_available" label="Available" :checked="$meal->is_available ?? true" />
            <x-admin.checkbox name="is_featured" label="Featured" :checked="$meal->is_featured ?? false" />
            <x-admin.checkbox name="is_hot" label="Hot / Ready-to-eat" :checked="$meal->is_hot ?? false" />
        </div>
    </x-admin.card>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $submitLabel }}</button>
        <a href="{{ route('admin.meals.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Cancel</a>
    </div>
</div>
