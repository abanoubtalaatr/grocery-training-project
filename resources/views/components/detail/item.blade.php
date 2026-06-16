@props([
    'label',
    'value' => null,
])

<div class="border rounded-2 p-3 h-100">
    <span class="d-block text-secondary small fw-semibold text-uppercase">{{ $label }}</span>
    <div class="mt-2">{{ $slot->isEmpty() ? ($value ?? 'Not set') : $slot }}</div>
</div>
