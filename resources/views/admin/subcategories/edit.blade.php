@extends('admin.layouts.app')

@section('title', 'Edit Subcategory')

@section('page-title', 'Edit Subcategory')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.subcategories.update', $subcategory) }}">

            @csrf
            @method('PUT')

            @include('admin.subcategories._form')

        </form>

    </div>

</div>

@endsection