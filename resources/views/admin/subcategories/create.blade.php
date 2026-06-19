@extends('admin.layouts.app')

@section('title', 'Create Subcategory')

@section('page-title', 'Create Subcategory')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.subcategories.store') }}">

            @csrf

            @include('admin.subcategories._form')

        </form>

    </div>

</div>

@endsection