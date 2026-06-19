@extends('admin.layouts.app')

@section('title', __('sidebar.categories'))

@section('page-title', __('sidebar.categories'))

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <div class="row align-items-center">

            <div class="col">
                <h5 class="mb-0">
                    {{ __('sidebar.categories') }}
                </h5>
            </div>

            <div class="col-auto">

                <form method="GET">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="{{ __('categories.search_placeholder') }}">

                </form>

                <a
                    href="{{ route('admin.categories.create') }}"
                    class="btn btn-primary">

                    Create Category

                </a>

            </div>

        </div>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead>

                <tr>
                    <th>{{ __('categories.image') }}</th>
                    <th>{{ __('categories.name') }}</th>
                    <th>{{ __('categories.meals_count') }}</th>
                    <th>{{ __('categories.status') }}</th>
                    <th>{{ __('categories.created_at') }}</th>
                    <th> {{ __('categories.actions') }}</th>
                </tr>

            </thead>

            <tbody>

                @forelse($categories as $category)

                    <tr>

                        <td>
                            <img
                                src="{{ $category->image_url }}"
                                alt="{{ $category->name }}"
                                width="50"
                                height="50"
                                class="rounded object-fit-cover">
                        </td>

                        <td>
                            {{ $category->name }}
                        </td>

                        <td>
                            {{ $category->meals_count }}
                        </td>

                        <td>

                            @if($category->is_active)

                                <span class="badge bg-success">
                                    {{ __('categories.active') }}
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    {{ __('categories.inactive') }}
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $category->created_at->format('Y-m-d') }}
                        </td>

                        <td>

                            <a
                                href="{{ route('admin.categories.edit', $category) }}"
                                class="btn btn-sm btn-warning">

                                Edit

                            </a>

                            <form
                                action="{{ route('admin.categories.destroy', $category) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this category?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center py-4">

                            {{ __('categories.no_categories_found') }}

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $categories->links() }}

</div>

@endsection