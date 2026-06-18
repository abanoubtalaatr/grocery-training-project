<x-admin.app-layout title="Offer">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">{{ $offer->title }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <x-admin.card>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">Code</dt><dd class="font-medium text-slate-900">{{ $offer->code }}</dd></div>
                <div><dt class="text-slate-500">Type</dt><dd class="font-medium text-slate-900">{{ ucfirst($offer->type) }}</dd></div>
                <div><dt class="text-slate-500">Discount</dt><dd class="font-medium text-slate-900">{{ $offer->type === 'percentage' ? $offer->discount_value . '%' : '$' . number_format((float) $offer->discount_value, 2) }}</dd></div>
                <div><dt class="text-slate-500">Min. Purchase</dt><dd class="font-medium text-slate-900">{{ $offer->minimum_purchase ? '$' . number_format((float) $offer->minimum_purchase, 2) : '—' }}</dd></div>
                <div><dt class="text-slate-500">Used</dt><dd class="font-medium text-slate-900">{{ $offer->used_count }} / {{ $offer->usage_limit ?? '∞' }}</dd></div>
                <div><dt class="text-slate-500">Validity</dt><dd class="font-medium text-slate-900">{{ optional($offer->start_date)->format('M d, Y') }} — {{ optional($offer->end_date)->format('M d, Y') }}</dd></div>
            </dl>
            @if ($offer->description)
                <p class="mt-4 text-sm text-slate-600">{{ $offer->description }}</p>
            @endif
        </x-admin.card>

        <div class="flex gap-3">
            <a href="{{ route('admin.offers.edit', $offer) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Edit</a>
            <a href="{{ route('admin.offers.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
        </div>
    </div>
</x-admin.app-layout>
