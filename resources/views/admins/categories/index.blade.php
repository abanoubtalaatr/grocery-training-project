@extends('layout.layout')

@section('title', 'Categories')
@section('breadcrumb', 'Admin / Categories')
@section('page-title', 'Categories')

@section('page-actions')
    <a href="{{ route('admins.categories.create') }}" class="btn btn-primary">Create Category</a>
@endsection

@section('content')
    @php
        $columns = [
            [
                'label' => 'Name',
                'key' => 'name',
                'link' => true,
                'sub_key' => 'id',
                'sub_prefix' => '#',
            ],
            [
                'label' => 'Slug',
                'key' => 'slug',
            ],
            [
                'label' => 'Status',
                'key' => 'is_active',
                'type' => 'boolean',
            ],
            [
                'label' => 'Sort',
                'key' => 'sort_order',
            ],
        ];
    @endphp

    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h2 class="h4 fw-bold mb-1">Category Records</h2>
                <p class="text-secondary mb-0">Manage grocery catalog categories.</p>
            </div>

            <form id="bulk-delete-form" action="{{ route('admins.categories.mass-destroy') }}" method="POST" onsubmit="return confirm('Delete selected categories?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Delete Selected</button>
            </form>
        </div>

        <x-table.index
            :records="$categories"
            :columns="$columns"
            empty-text="No categories found."
            selectable
            checkbox-form="bulk-delete-form"
            show-route="admins.categories.show"
            edit-route="admins.categories.edit"
            delete-route="admins.categories.destroy"
            delete-message="Delete this category?"
        />

        <div>
            {{ $categories->links() }}
        </div>
    </div>
@endsection
