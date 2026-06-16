@extends('admin.layouts.app')

@section('title', __('sidebar.dashboard'))

@section('page-title', __('sidebar.dashboard'))

@section('content')

<div class="container-fluid py-4">
<div class="row g-4">

    <div class="col-md-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <h6>{{__('dashboard.users')}}</h6>

                <h2>{{ $stats['users'] }}</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <h6>{{__('dashboard.orders')}}</h6>

                <h2>{{ $stats['orders'] }}</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <h6>{{ __('dashboard.meals') }}</h6>

                <h2>{{ $stats['meals'] }}</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <h6>{{__('dashboard.categories')}}</h6>

                <h2>{{ $stats['categories'] }}</h2>

            </div>

        </div>

    </div>

</div>
</div>

@endsection