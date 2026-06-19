@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('page-title', 'Create Category')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.categories.store') }}">

            @csrf

            @include('admin.categories._form')

        </form>

    </div>

</div>

@endsection