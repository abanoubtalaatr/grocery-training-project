@extends('admin.layouts.app')

@section('title', 'Orders')

@section('page-title', 'Orders')

@section('content')

<div class="table-card">

    <div class="d-flex justify-content-between mb-3">

        <h4>Orders</h4>

        <span>
            Total:
            {{ $orders->total() }}
        </span>

    </div>

    <table class="table table-hover">

        <thead>

        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

        </thead>

        <tbody>

        @foreach($orders as $order)

            <tr>

                <td>
                    #{{ $order->id }}
                </td>

                <td>
                    {{ $order->user?->username }}
                </td>

                <td>
                    {{ $order->user?->email }}
                </td>

                <td>
                    {{ $order->total }}
                </td>

                <td>

                    <span class="badge bg-success">
                        {{ $order->status }}
                    </span>

                </td>

                <td>
                    {{ $order->created_at?->format('Y-m-d') }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    {{ $orders->links() }}

</div>

@endsection