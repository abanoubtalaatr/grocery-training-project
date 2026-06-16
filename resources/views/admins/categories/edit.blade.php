@extends('layout.layout')

@section('title', 'Edit Category')
@section('breadcrumb', 'Admin / Categories / Edit')
@section('page-title', 'Edit Category')

@section('page-actions')
    <a href="{{ route('admins.categories.show', $category) }}" class="btn btn-outline-secondary">View Category</a>
@endsection

@section('content')
    <x-categories.form
        :category="$category"
        :action="route('admins.categories.update', $category)"
        method="PUT"
        submit-label="Update Category"
    />
@endsection
