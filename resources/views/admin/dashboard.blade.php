@extends('admin.layouts.app')

@section('title', __('sidebar.dashboard'))

@section('page-title', __('sidebar.dashboard'))

@section('content')

    <div class="container-fluid py-4">

        {{-- Statistics Cards --}}
        <div class="row g-4">

            <div class="col-md-3">
                

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-muted">
                            {{ __('dashboard.users') }}
                        </h6>

                        <h2>{{ $stats['users'] }}</h2>

                    </div>

                    <i class="bi bi-people fs-1 text-primary"></i>

                </div>

            </div>

            <div class="col-md-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-muted">
                            {{ __('dashboard.orders') }}
                        </h6>

                        <h2>{{ $stats['orders'] }}</h2>

                    </div>

                    <i class="bi bi-bag-check fs-1 text-primary"></i>

                </div>

            </div>

            <div class="col-md-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-muted">
                            {{ __('dashboard.meals') }}
                        </h6>

                        <h2>{{ $stats['meals'] }}</h2>

                    </div>

                    <i class="bi bi-cup-hot fs-1 text-primary"></i>

                </div>

            </div>

            <div class="col-md-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-muted">
                            {{ __('dashboard.total_sales') }}
                        </h6>

                        <h2 class="mb-0">
                            EGP {{ number_format($stats['total_sales'], 2) }}
                        </h2>

                    </div>
                    <i class="bi bi-cash-stack fs-1 text-primary"></i>

                </div>

            </div>

        </div>

        {{-- Dashboard Widgets --}}
        <div class="row mt-4">

            {{-- Recent Orders --}}
            <div class="col-lg-8">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            {{ __('dashboard.recent_orders') }}
                        </h5>

                    </div>

                    <div class="card-body p-0">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>
                                    <th>{{ __('orders.order_number') }}</th>
                                    <th>{{ __('orders.customer') }}</th>
                                    <th>{{ __('orders.status') }}</th>
                                    <th>{{ __('orders.total') }}</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($stats['latest_orders'] as $order)
                                    <tr>

                                        <td>
                                            {{ $order->order_number }}
                                        </td>

                                        <td>

                                            @if ($order->user)
                                                <div>

                                                    <strong>
                                                        {{ $order->user->firstname }}
                                                        {{ $order->user->lastname }}
                                                    </strong>

                                                </div>

                                                <small class="text-muted">
                                                    {{ $order->user->email }}
                                                </small>
                                            @else
                                                -
                                            @endif

                                        </td>

                                        <td>

                                            <span class="badge bg-{{ $order->status_badge_class }}">

                                                {{ $order->status_description }}

                                            </span>

                                        </td>

                                        <td>

                                            EGP {{ number_format($order->total, 2) }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center py-5">

                                            <i class="bi bi-bag-x fs-1 text-muted"></i>

                                            <div class="mt-2">

                                            {{ __('orders.no_orders_found') }}
                                            </div>

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            {{-- Quick Stats --}}
            <div class="col-lg-4">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            {{ __('dashboard.quick_stats') }}
                        </h5>

                    </div>

                    <div class="card-body">

                        <ul class="list-group list-group-flush">

                            <li class="list-group-item d-flex justify-content-between">

                                <span>{{ __('dashboard.active_users') }}</span>

                                <strong>{{ $stats['active_users'] }}</strong>

                            </li>

                            <li class="list-group-item d-flex justify-content-between">

                                <span>{{ __('dashboard.active_meals') }}</span>

                                <strong class="fw-semibold">
                                    {{ $stats['available_meals'] }}
                                </strong>

                            </li>

                            <li class="list-group-item d-flex justify-content-between">

                                <span>{{ __('dashboard.active_categories') }}</span>

                                <strong>{{ $stats['active_categories'] }}</strong>

                            </li>

                        </ul>
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
