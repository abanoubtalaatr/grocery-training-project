@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('page-title', 'Edit Category')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.categories.update', $category) }}">

            @csrf
            @method('PUT')

            @include('admin.categories._form')

        </form>

    </div>

</div>

@endsection