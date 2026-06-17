@extends('admin.layouts.app')

@section('title', __('sidebar.orders'))

@section('page-title', __('sidebar.orders'))

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <div class="row align-items-center">

            <div class="col">

                <h5 class="mb-0">
                    {{ __('sidebar.orders') }}
                </h5>

                <small class="text-muted">
                    {{ $orders->total() }}
                    {{ __('sidebar.orders') }}
                </small>

            </div>

            <div class="col-auto">

                <form method="GET">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="{{ __('orders.search_placeholder') }}">

                </form>

            </div>

        </div>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead>
                <tr>
                    <th>{{ __('orders.order_number') }}</th>
                    <th>{{ __('orders.customer') }}</th>
                    <th>{{ __('orders.items') }}</th>
                    <th>{{ __('orders.total') }}</th>
                    <th>{{ __('orders.status') }}</th>
                    <th>{{ __('orders.date') }}</th>
                </tr>
            </thead>

            <tbody>

@forelse($orders as $order)

<tr>

    <td>
        {{ $order->order_number }}
    </td>

    <td>

        @if($order->user)

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
        {{ $order->items_count }}
    </td>

    <td>
        EGP {{ number_format($order->total, 2) }}
    </td>

    <td>

    <span
        class="badge bg-{{ $order->status_badge_class }}">

        {{ $order->status_description }}

    </span>

</td>

    <td>
        {{ $order->created_at->format('Y-m-d') }}
    </td>

</tr>

@empty

<tr>

    <td colspan="6" class="text-center py-4">

        <div class="py-3">

            <h6 class="mb-1">
                {{ __('orders.no_orders_found') }}
            </h6>

            <small class="text-muted">
                No orders have been placed yet.
            </small>

        </div>

    </td>

</tr>

@endforelse

</tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $orders->links() }}

</div>

@endsection