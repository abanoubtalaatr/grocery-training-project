@extends('admin.layouts.app')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.meals.store') }}">

            @csrf

            @include('admin.meals._form')

        </form>

    </div>

</div>

@endsection