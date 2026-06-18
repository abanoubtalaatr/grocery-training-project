<x-admin.card class="max-w-3xl">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <x-admin.input name="title" label="Title" :value="$offer->title" required />
        <x-admin.input name="code" label="Code" :value="$offer->code" required hint="Unique coupon code." />

        <div class="md:col-span-2">
            <x-admin.textarea name="description" label="Description" :value="$offer->description" />
        </div>

        <x-admin.select name="type" label="Discount Type" :options="['percentage' => 'Percentage (%)', 'fixed' => 'Fixed amount ($)']" :selected="$offer->type ?? 'percentage'" required />
        <x-admin.input name="discount_value" label="Discount Value" type="number" step="0.01" :value="$offer->discount_value" required />

        <x-admin.input name="minimum_purchase" label="Minimum Purchase" type="number" step="0.01" :value="$offer->minimum_purchase" />
        <x-admin.input name="usage_limit" label="Usage Limit" type="number" :value="$offer->usage_limit" hint="Leave empty for unlimited." />

        <x-admin.input name="start_date" label="Start Date" type="date" :value="optional($offer->start_date)->format('Y-m-d')" required />
        <x-admin.input name="end_date" label="End Date" type="date" :value="optional($offer->end_date)->format('Y-m-d')" required />

        <div class="flex items-center gap-6 md:col-span-2">
            <x-admin.checkbox name="is_active" label="Active" :checked="$offer->is_active ?? true" />
            <x-admin.checkbox name="is_featured" label="Featured" :checked="$offer->is_featured ?? false" />
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3 border-t border-slate-200 pt-5">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $submitLabel }}</button>
        <a href="{{ route('admin.offers.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Cancel</a>
    </div>
</x-admin.card>
