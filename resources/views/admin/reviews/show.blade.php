@extends('admin.layouts.app')

@section('title', __('reviews.review_details'))

@section('page-title', __('reviews.review_details'))

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <p>
            <strong>{{__('reviews.users')}}:</strong>
            {{ $review->user?->full_name }}
        </p>

        <p>
            <strong>{{__('reviews.meals')}}:</strong>
            {{ $review->meal?->title }}
        </p>

        <p>
            <strong>{{__('reviews.rating')}}:</strong>
            ⭐ {{ $review->rating }}
        </p>

        <p>
            <strong>{{__('reviews.comment')}}:</strong>
            {{ $review->comment }}
        </p>

        <p>
            <strong>{{__('reviews.status')}}:</strong>

            @if($review->is_approved)

                Approved

            @else

                Pending

            @endif

        </p>

    </div>

</div>

@endsection