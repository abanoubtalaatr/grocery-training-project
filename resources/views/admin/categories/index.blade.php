@extends('admin.layouts.app')

@section('title', __('general.categories'))

@section('page-title', __('general.categories'))

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">
            {{ __('general.categories') }}
        </h4>

    </div>

    <div class="card-body">
        <a href="{{ route('admin.categories.create') }}"
        class="btn btn-primary mb-3">

            {{ __('general.create') }}

        </a>
        @if($categories->count())

            <div class="table-responsive">

                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('general.name') }}</th>
                        <th>{{ __('general.created_at') }}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($categories as $category)

                        <tr>

                            <td>
                                {{ $category->id }}
                            </td>

                            <td>
                                {{ $category->name }}
                            </td>

                            <td>
                                {{ $category->created_at->format('Y-m-d') }}
                            </td>
                            <td>

                                <a href="{{ route('admin.categories.edit', $category) }}"
                                class="btn btn-sm btn-warning">

                                {{ __('general.edit') }}
                                    

                                </a>

                                <form method="POST"
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    class="d-inline">

                                    @csrf

                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete category?')">

                                        {{ __('general.delete') }}

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

            {{ $categories->links() }}

        @else

            <div class="alert alert-info">

                {{ __('general.no_categories') }}

            </div>

        @endif

    </div>

</div>

@endsection