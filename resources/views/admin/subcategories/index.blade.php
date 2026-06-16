@extends('admin.layouts.app')

@section('title', 'Subcategories')

@section('page-title', 'Subcategories')

@section('content')

<div class="table-card">

    <div class="d-flex justify-content-between mb-3">

        <h4>Subcategories</h4>

        <span>
            Total:
            {{ $subcategories->total() }}
        </span>

    </div>

    <table class="table table-hover">

        <thead>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Slug</th>
            <th>Created</th>
        </tr>

        </thead>

        <tbody>

        @foreach($subcategories as $subcategory)

            <tr>

                <td>{{ $subcategory->id }}</td>

                <td>{{ $subcategory->name }}</td>

                <td>
                    {{ $subcategory->category?->name }}
                </td>

                <td>{{ $subcategory->slug }}</td>

                <td>
                    {{ $subcategory->created_at?->format('Y-m-d') }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    {{ $subcategories->links() }}

</div>

@endsection