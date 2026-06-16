@extends('admin.layouts.app')

@section('title', __('Create Category'))

@section('page-title', __('Create Category'))

@section('content')

<div class="card">

    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.categories.store') }}"
              enctype="multipart/form-data">

            @csrf

            @include(
                'admin.categories._form',
                ['button' => __('Create')]
            )

        </form>

    </div>

</div>

@endsection