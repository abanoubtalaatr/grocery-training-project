@props([
    'name',
    'label' => null,
    'value' => null,
    'rows' => 4,
    'required' => false,
    'hint' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-slate-700">
            {{ $label }}@if ($required)<span class="text-red-500"> *</span>@endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm transition focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500']) }}
    >{{ old($name, $value) }}</textarea>

    @if ($hint)
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
