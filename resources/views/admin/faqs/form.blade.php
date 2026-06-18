<x-admin.card class="max-w-3xl">
    <div class="space-y-5">
        <x-admin.input name="question" label="Question" :value="$faq->question" required />
        <x-admin.textarea name="answer" label="Answer" :value="$faq->answer" rows="5" required />

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-admin.input name="category" label="Category" :value="$faq->category" hint="Optional grouping, e.g. Orders, Payments." />
            <x-admin.input name="order" label="Order" type="number" :value="$faq->order ?? 0" />
        </div>

        <x-admin.checkbox name="is_active" label="Active" :checked="$faq->is_active ?? true" />
    </div>

    <div class="mt-6 flex items-center gap-3 border-t border-slate-200 pt-5">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $submitLabel }}</button>
        <a href="{{ route('admin.faqs.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Cancel</a>
    </div>
</x-admin.card>
