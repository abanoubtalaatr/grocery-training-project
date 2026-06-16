@extends('layout.layout')
@section('title', 'Orders')
@section('breadcrumb', 'Admin / Orders')
@section('page-title', 'Orders')

@section('content')
    @php
        $columns = [
            [
                'label' => 'Order Number',
                'key' => 'order_number',
                'link' => true,
                'sub_key' => 'id',
                'sub_prefix' => '#',
            ],
            [
                'label' => 'User',
                'key' => 'user_id',
            ],
            [
                'label' => 'Address',
                'key' => 'address_id',
            ],
            [
                'label' => 'Status',
                'key' => 'status',
            ],
            [
                'label' => 'Payment',
                'key' => 'payment_method',
            ],
            [
                'label' => 'Total',
                'key' => 'total',
            ],
        ];
    @endphp

    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h2 class="h4 fw-bold mb-1">Order Records</h2>
                <p class="text-secondary mb-0">Manage customer orders.</p>
            </div>

            <form id="bulk-delete-form"
                  action="{{ route('admins.orders.mass-destroy') }}"
                  method="POST"
                  onsubmit="return confirm('Delete selected orders?')">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-outline-danger">
                    Delete Selected
                </button>
            </form>
        </div>

        <x-table.index
            :records="$orders"
            :columns="$columns"
            empty-text="No orders found."
            selectable
            checkbox-form="bulk-delete-form"
            show-route="admins.orders.show"
            edit-route="admins.orders.edit"
            delete-route="admins.orders.destroy"
            delete-message="Delete this order?"
        />

        <div>
            {{ $orders->links() }}
        </div>
    </div>
@endsection
