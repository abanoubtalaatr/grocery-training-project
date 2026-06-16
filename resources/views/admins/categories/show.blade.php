@extends('layout.layout')

@section('title', $category->name)
@section('breadcrumb', 'Admin / Categories / Show')
@section('page-title', $category->name)

@section('page-actions')
    <a href="{{ route('admins.categories.edit', $category) }}" class="btn btn-primary">Edit</a>
    <a href="{{ route('admins.categories.index') }}" class="btn btn-outline-secondary">Back to Categories</a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <x-detail.item label="Image">
                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid rounded-2 border">
            </x-detail.item>
        </div>

        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-detail.item label="Name" :value="$category->name" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Slug" :value="$category->slug" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Status">
                        @if ($category->is_active)
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-secondary">Inactive</span>
                        @endif
                    </x-detail.item>
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Sort Order" :value="$category->sort_order" />
                </div>

                <div class="col-12">
                    <x-detail.item label="Description" :value="$category->description" />
                </div>
            </div>
        </div>
    </div>
@endsection
