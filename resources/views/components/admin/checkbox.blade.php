@props([
    'name',
    'label',
    'checked' => false,
    'hint' => null,
])

<div>
    <label class="inline-flex items-center gap-2">
        <input type="hidden" name="{{ $name }}" value="0">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="1"
            @checked(old($name, $checked))
            {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500']) }}
        />
        <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
    </label>

    @if ($hint)
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
