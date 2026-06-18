<x-admin.app-layout title="Meal">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">{{ $meal->title }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <x-admin.card>
            <div class="flex items-start gap-4">
                @if ($meal->image_url)
                    <img src="{{ $meal->image_url }}" alt="" class="h-24 w-24 rounded-lg object-cover">
                @endif
                <div class="space-y-1">
                    <p class="text-lg font-semibold text-slate-900">{{ $meal->title }}</p>
                    <p class="text-sm text-slate-500">{{ $meal->category?->name }} @if($meal->subcategory) / {{ $meal->subcategory->name }} @endif</p>
                    <p class="text-base font-semibold text-slate-900">${{ number_format((float) $meal->price, 2) }}</p>
                </div>
            </div>
            @if ($meal->description)
                <p class="mt-4 text-sm text-slate-600">{{ $meal->description }}</p>
            @endif
            <dl class="mt-4 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                <div><dt class="text-slate-500">Stock</dt><dd class="font-medium text-slate-900">{{ $meal->stock_quantity }}</dd></div>
                <div><dt class="text-slate-500">Sold</dt><dd class="font-medium text-slate-900">{{ $meal->sold_count }}</dd></div>
                <div><dt class="text-slate-500">Rating</dt><dd class="font-medium text-slate-900">{{ $meal->rating }} ({{ $meal->rating_count }})</dd></div>
            </dl>
        </x-admin.card>

        <div class="flex gap-3">
            <a href="{{ route('admin.meals.edit', $meal) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Edit</a>
            <a href="{{ route('admin.meals.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Back</a>
        </div>
    </div>
</x-admin.app-layout>
