@props([
    'record',
    'columns',
    'selectable' => false,
    'checkboxName' => 'ids[]',
    'checkboxForm' => null,
    'showRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'deleteMessage' => 'Delete this record?',
])

@php
    $recordLabel = data_get($record, 'name', 'record');
@endphp

<tr>
    @if ($selectable)
        <td>
            <input
                class="form-check-input"
                type="checkbox"
                name="{{ $checkboxName }}"
                value="{{ $record->getKey() }}"
                @if ($checkboxForm) form="{{ $checkboxForm }}" @endif
                aria-label="Select {{ $recordLabel }}"
            >
        </td>
    @endif

    @foreach ($columns as $column)
        @php
            $value = data_get($record, $column['key']);
            $type = $column['type'] ?? 'text';
            $isLinked = ($column['link'] ?? false) && $showRoute;
        @endphp

        <td class="{{ $column['class'] ?? '' }}">
            @if ($type === 'boolean')
                @if ($value)
                    <span class="badge text-bg-success">{{ $column['true_label'] ?? 'Active' }}</span>
                @else
                    <span class="badge text-bg-secondary">{{ $column['false_label'] ?? 'Inactive' }}</span>
                @endif
            @elseif ($isLinked)
                <a href="{{ route($showRoute, $record) }}" class="fw-semibold text-decoration-none">
                    {{ $value }}
                </a>

                @if (isset($column['sub_key']))
                    <div class="text-secondary small">{{ $column['sub_prefix'] ?? '' }}{{ data_get($record, $column['sub_key']) }}</div>
                @endif
            @else
                {{ $value ?? ($column['empty'] ?? 'Not set') }}
            @endif
        </td>
    @endforeach

    @if ($editRoute || $deleteRoute)
        <td class="text-end">
            <div class="d-inline-flex gap-2">
                @if ($editRoute)
                    <a href="{{ route($editRoute, $record) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                @endif

                @if ($deleteRoute)
                    <form action="{{ route($deleteRoute, $record) }}" method="POST" onsubmit="return confirm('{{ $deleteMessage }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                @endif
            </div>
        </td>
    @endif
</tr>
