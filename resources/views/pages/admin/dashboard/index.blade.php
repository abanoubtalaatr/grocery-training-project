@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('page-title', __('app.dashboard'))

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

    <div class="bg-white p-5 rounded-xl shadow">
        <p class="text-sm text-gray-500">
            {{ __('app.products') }}
        </p>

        <h2 class="text-3xl font-bold mt-2">
            120
        </h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow">
        <p class="text-sm text-gray-500">
            {{ __('app.orders') }}
        </p>

        <h2 class="text-3xl font-bold mt-2">
            530
        </h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow">
        <p class="text-sm text-gray-500">
            {{ __('app.customers') }}
        </p>

        <h2 class="text-3xl font-bold mt-2">
            350
        </h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow">
        <p class="text-sm text-gray-500">
            {{ __('app.revenue') }}
        </p>

        <h2 class="text-3xl font-bold mt-2">
            $12,500
        </h2>
    </div>

</div>

@endsection
