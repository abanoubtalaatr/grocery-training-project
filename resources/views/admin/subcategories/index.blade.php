@extends('admin.layouts.app')

@section('title', 'Subcategories')

@section('page-title', 'Subcategories')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <div class="row align-items-center">

            <div class="col">

                <h5 class="mb-0">
                    {{ __('subcategories.title') }}
                </h5>

            </div>

            <div class="col-auto">

                <a
                    href="{{ route('admin.subcategories.create') }}"
                    class="btn btn-primary">

                    <i class="bi bi-plus-circle"></i>
                    {{ __('subcategories.add_subcategory') }}

                </a>

            </div>

        </div>

        <form
            method="GET"
            class="mt-3">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search subcategories...">

        </form>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead>

                <tr>

                    <th>#</th>

                    <th>{{ __('subcategories.category') }}</th>

                    <th>{{ __('subcategories.name') }}</th>

                    <th>{{ __('subcategories.status') }}</th>

                    <th>Created At</th>

                    <th width="150">
                        {{ __('subcategories.actions') }}
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($subcategories as $subcategory)

                    <tr>

                        <td>
                            {{ $subcategory->id }}
                        </td>

                        <td>
                            {{ $subcategory->category?->name }}
                        </td>

                        <td>
                            {{ $subcategory->name }}
                        </td>

                        <td>

                            @if($subcategory->is_active)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $subcategory->created_at?->format('Y-m-d') }}
                        </td>

                        <td>

                            <a
                                href="{{ route('admin.subcategories.edit', $subcategory) }}"
                                class="btn btn-sm btn-warning">

                                Edit

                            </a>

                            <form
                                action="{{ route('admin.subcategories.destroy', $subcategory) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this subcategory?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-4">

                            {{ __('subcategories.no_subcategories_found') }}

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $subcategories->links() }}

</div>

@endsection