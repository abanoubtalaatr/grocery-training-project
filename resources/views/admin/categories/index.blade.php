@extends('admin.layouts.app')

@section('title', 'Categories')

@section('page-title', 'Categories')

@section('content')

<div class="table-card">

    <div class="d-flex justify-content-between mb-3">
        <h4>Categories</h4>

        <span>
            Total:
            {{ $categories->total() }}
        </span>
    </div>

    <table class="table table-hover">

        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Created</th>
        </tr>
        </thead>

        <tbody>

        @foreach($categories as $category)

            <tr>

                <td>{{ $category->id }}</td>

                <td>{{ $category->name }}</td>

                <td>{{ $category->slug }}</td>

                <td>
                    {{ $category->created_at?->format('Y-m-d') }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    {{ $categories->links() }}

</div>

@endsection