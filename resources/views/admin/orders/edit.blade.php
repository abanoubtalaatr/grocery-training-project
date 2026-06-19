@extends('admin.layouts.app')

@section('title', 'Update Order Status')

@section('page-title', 'Update Order Status')

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.orders.update', $order) }}">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">

                    Order Number

                </label>

                <input
                    type="text"
                    value="{{ $order->order_number }}"
                    class="form-control"
                    disabled>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Status

                </label>

                <select
                    name="status"
                    class="form-select">

                    <option value="placed"
                        @selected($order->status == 'placed')>
                        Placed
                    </option>

                    <option value="processing"
                        @selected($order->status == 'processing')>
                        Processing
                    </option>

                    <option value="shipping"
                        @selected($order->status == 'shipping')>
                        Shipping
                    </option>

                    <option value="out_for_delivery"
                        @selected($order->status == 'out_for_delivery')>
                        Out For Delivery
                    </option>

                    <option value="delivered"
                        @selected($order->status == 'delivered')>
                        Delivered
                    </option>

                    <option value="cancelled"
                        @selected($order->status == 'cancelled')>
                        Cancelled
                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="btn btn-primary">

                Save

            </button>

        </form>

    </div>

</div>

@endsection