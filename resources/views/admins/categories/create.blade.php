@extends('layout.layout')

@section('title', 'Create Category')
@section('breadcrumb', 'Admin / Categories / Create')
@section('page-title', 'Create Category')

@section('page-actions')
    <a href="{{ route('admins.categories.index') }}" class="btn btn-outline-secondary">Back to Categories</a>
@endsection

@section('content')
    <x-categories.form
        :action="route('admins.categories.store')"
        submit-label="Create Category"
    />
@endsection
