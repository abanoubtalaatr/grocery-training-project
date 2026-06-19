@extends('admin.layouts.app')

@section('title', __('reviews.title'))

@section('page-title', __('reviews.title'))

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <form method="GET">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="{{ __('reviews.search_placeholder') }}">

        </form>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead>

                <tr>

                    <th>{{__('reviews.users')}}</th>

                    <th>{{__('reviews.meals')}}</th>

                    <th>{{__('reviews.rating')}}</th>

                    <th>{{__('reviews.status')}}</th>

                    <th>{{__('reviews.date')}}</th>

                    <th>{{__('reviews.actions')}}</th>

                </tr>

            </thead>

            <tbody>

                @forelse($reviews as $review)

                    <tr>

                        <td>
                            {{ $review->user?->full_name }}
                        </td>

                        <td>
                            {{ $review->meal?->title }}
                        </td>

                        <td>
                            ⭐ {{ $review->rating }}
                        </td>

                        <td>

                            @if($review->is_approved)

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @else

                                <span class="badge bg-warning">
                                    Pending
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $review->created_at->format('Y-m-d') }}
                        </td>

                        <td>

                            <a
                                href="{{ route('admin.reviews.show', $review) }}"
                                class="btn btn-sm btn-info">

                                View

                            </a>

                            @if(!$review->is_approved)

                                <form
                                    method="POST"
                                    action="{{ route('admin.reviews.approve', $review) }}"
                                    class="d-inline">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="btn btn-sm btn-success">

                                        Approve

                                    </button>

                                </form>

                            @else

                                <form
                                    method="POST"
                                    action="{{ route('admin.reviews.reject', $review) }}"
                                    class="d-inline">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="btn btn-sm btn-warning">

                                        Reject

                                    </button>

                                </form>

                            @endif

                            <form
                                method="POST"
                                action="{{ route('admin.reviews.destroy', $review) }}"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-sm btn-danger">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            {{ __('reviews.no_reviews_found') }}

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $reviews->links() }}

</div>

@endsection