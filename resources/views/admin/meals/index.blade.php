@extends('admin.layouts.app')

@section('title', __('sidebar.meals'))

@section('page-title', __('sidebar.meals'))

@section('content')

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="row align-items-center">

                <div class="col">
                    <h5 class="mb-0">
                        {{ __('sidebar.meals') }}
                    </h5>
                </div>

                <div class="col-auto">

                    <form method="GET">

                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="{{ __('meals.search_placeholder') }}">

                    </form>


                    <a
                        href="{{ route('admin.meals.create') }}"
                        class="btn btn-primary">

                        Create Meal

                    </a>

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th>{{ __('meals.image') }}</th>
                        <th>{{ __('meals.name') }}</th>
                        <th>{{ __('meals.meals_count') }}</th>
                        <th>{{ __('meals.status') }}</th>
                        <th>{{ __('meals.created_at') }}</th>
                        <th>{{ __('meals.active') }}</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($meals as $meal)
                        <tr>

                            <td>

                                @if ($meal->image_url)
                                    <img src="{{ $meal->image_url }}" width="50" height="50" class="rounded">
                                @else
                                    —
                                @endif

                            </td>

                            <td>
                                {{ $meal->title }}
                            </td>

                            <td>
                                {{ $meal->category?->name ?? '-' }}
                            </td>

                            <td>
                                ${{ number_format($meal->final_price, 2) }}
                            </td>

                            <td>
                                @if ($meal->stock_quantity > 20)
                                    <span class="badge bg-success">
                                        {{ $meal->stock_quantity }}
                                    </span>
                                @elseif($meal->stock_quantity > 0)
                                    <span class="badge bg-warning">
                                        {{ $meal->stock_quantity }}
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Out of Stock
                                    </span>
                                @endif
                            </td>

                            <td>

                                @if ($meal->is_available)
                                    <span class="badge bg-success">
                                        Available
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Unavailable
                                    </span>
                                @endif

                            </td>

                            <td>

                                <a href="{{ route('admin.meals.edit', $meal) }}" class="btn btn-sm btn-warning">

                                    Edit

                                </a>

                                <form action="{{ route('admin.meals.destroy', $meal) }}" method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this meal?')">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-4">

                                No meals found

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="mt-3">

        {{ $meals->links() }}

    </div>

@endsection
