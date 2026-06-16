@props([
    'records',
    'columns',
    'emptyText' => 'No records found.',
    'selectable' => false,
    'checkboxName' => 'ids[]',
    'checkboxForm' => null,
    'showRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'deleteMessage' => 'Delete this record?',
])

@php
    $hasActions = $editRoute || $deleteRoute;
    $columnCount = count($columns) + ($selectable ? 1 : 0) + ($hasActions ? 1 : 0);
@endphp

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table align-middle mb-0']) }}>
        <thead>
            <tr>
                @if ($selectable)
                    <th scope="col">
                        <span class="visually-hidden">Select</span>
                    </th>
                @endif

                @foreach ($columns as $column)
                    <th scope="col" class="{{ $column['header_class'] ?? '' }}">
                        {{ $column['label'] }}
                    </th>
                @endforeach

                @if ($hasActions)
                    <th scope="col" class="text-end">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <x-table.row
                    :record="$record"
                    :columns="$columns"
                    :selectable="$selectable"
                    :checkbox-name="$checkboxName"
                    :checkbox-form="$checkboxForm"
                    :show-route="$showRoute"
                    :edit-route="$editRoute"
                    :delete-route="$deleteRoute"
                    :delete-message="$deleteMessage"
                />
            @empty
                <tr>
                    <td colspan="{{ $columnCount }}" class="text-center text-secondary py-5">
                        {{ $emptyText }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
