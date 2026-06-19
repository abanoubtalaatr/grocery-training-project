@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('page-title', 'Order Details')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <h5>Order Information</h5>

                <p>
                    <strong>Order Number:</strong>
                    {{ $order->order_number }}
                </p>

                <p>
                    <strong>Status:</strong>
                    {{ $order->status_description }}
                </p>

                <p>
                    <strong>Total:</strong>
                    EGP {{ number_format($order->total, 2) }}
                </p>

            </div>

            <div class="col-md-6">

                <h5>Customer</h5>

                @if($order->user)

                    <p>
                        {{ $order->user->firstname }}
                        {{ $order->user->lastname }}
                    </p>

                    <p>
                        {{ $order->user->email }}
                    </p>

                @endif

            </div>

        </div>

        <hr>

        <h5>Order Items</h5>

        <table class="table">

            <thead>

                <tr>

                    <th>Meal</th>

                    <th>Qty</th>

                    <th>Price</th>

                    <th>Subtotal</th>

                </tr>

            </thead>

            <tbody>

                @foreach($order->items as $item)

                    <tr>

                        <td>
                            {{ $item->meal?->title }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td>
                            EGP {{ number_format($item->unit_price, 2) }}
                        </td>

                        <td>
                            EGP {{ number_format($item->subtotal, 2) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection