@props([
    'name',
    'label',
    'checked' => false,
])

<input type="hidden" name="{{ $name }}" value="0">

<div class="form-check mb-3">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="1"
        @checked((bool) old($name, $checked))
        {{ $attributes->merge(['class' => 'form-check-input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >
    <label for="{{ $name }}" class="form-check-label fw-semibold">{{ $label }}</label>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
