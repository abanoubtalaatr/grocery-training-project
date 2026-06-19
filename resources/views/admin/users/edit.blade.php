@extends('admin.layouts.app')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.users.update', $user) }}">

            @csrf
            @method('PUT')

            @include('admin.users._form')

        </form>

    </div>

</div>

@endsection