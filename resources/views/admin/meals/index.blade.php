@extends('admin.layouts.app')

@section('title', 'Meals')

@section('page-title', 'Meals')

@section('content')

<div class="table-card">

    <div class="d-flex justify-content-between mb-3">

        <h4>Meals</h4>

        <span>
            Total:
            {{ $meals->total() }}
        </span>

    </div>

    <table class="table table-hover">

        <thead>

        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Price</th>
            <th>Stock</th>
        </tr>

        </thead>

        <tbody>

        @foreach($meals as $meal)

            <tr>

                <td>{{ $meal->id }}</td>

                <td>

                    @if($meal->image_url)

                        <img
                            src="{{ $meal->image_url }}"
                            width="50"
                            height="50"
                            style="object-fit:cover;border-radius:8px;"
                        >

                    @endif

                </td>

                <td>
                    {{ $meal->title }}
                </td>

                <td>
                    {{ $meal->category?->name }}
                </td>

                <td>
                    {{ $meal->subcategory?->name }}
                </td>

                <td>
                    {{ $meal->price }}
                </td>

                <td>

                    @if($meal->stock_quantity > 0)

                        <span class="badge bg-success">
                            {{ $meal->stock_quantity }}
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Out Of Stock
                        </span>

                    @endif

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    {{ $meals->links() }}

</div>

@endsection