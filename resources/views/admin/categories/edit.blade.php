@extends('admin.layouts.app')

@section('title', __('Edit Category'))

@section('page-title', __('Edit Category'))

@section('content')

<div class="card">

    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.categories.update', $category) }}"
              enctype="multipart/form-data">

            @csrf

            @method('PUT')

            @include(
                'admin.categories._form',
                [
                    'button' => __('Update'),
                    'category' => $category,
                ]
            )

        </form>

    </div>

</div>

@endsection