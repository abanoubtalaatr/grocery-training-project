
@extends('layout.layout')

@section('title', $order->order_number)
@section('breadcrumb', 'Admin / Orders / Show')
@section('page-title', $order->order_number)

@section('page-actions')
    <a href="{{ route('admins.orders.edit', $order) }}" class="btn btn-primary">Edit</a>
    <a href="{{ route('admins.orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <x-detail.item label="Order Number" :value="$order->order_number"/>
    </div>

    <div class="col-md-6">
        <x-detail.item label="Status" :value="$order->status"/>
    </div>

    <div class="col-md-6">
        <x-detail.item label="Payment Method" :value="$order->payment_method"/>
    </div>

    <div class="col-md-6">
        <x-detail.item label="Delivery Type" :value="$order->delivery_type"/>
    </div>

    <div class="col-md-6">
        <x-detail.item label="Subtotal" :value="$order->subtotal"/>
    </div>

    <div class="col-md-6">
        <x-detail.item label="Tax" :value="$order->tax"/>
    </div>

    <div class="col-md-6">
        <x-detail.item label="Discount" :value="$order->discount"/>
    </div>

    <div class="col-md-6">
        <x-detail.item label="Shipping Fee" :value="$order->shipping_fee"/>
    </div>

    <div class="col-md-6">
        <x-detail.item label="Total" :value="$order->total"/>
    </div>

    <div class="col-12">
        <x-detail.item label="Notes" :value="$order->notes"/>
    </div>
</div>
@endsection
