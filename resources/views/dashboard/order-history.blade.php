@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 text-white font-weight-bold">Order History</h2>
                    <p class="text-muted mb-0">Track and manage your past orders and deliveries.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-shopping-bag me-1"></i> Order Tracking
                </div>
            </div>

            <!-- Orders List -->
            @forelse($orders as $order)
                <div class="card border-0 shadow-sm text-white mb-4" style="background: linear-gradient(145deg, #022c22 0%, #064e3b 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px; overflow: hidden;">
                    <!-- Order Top Row -->
                    <div class="card-header bg-dark p-4 border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div class="d-flex flex-wrap gap-4">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Order Placed</p>
                                <span class="text-white fw-bold">{{ $order->placed_at?->format('M d, Y') ?? $order->created_at?->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Order Number</p>
                                <span class="text-emerald fw-bold">#{{ $order->order_number }}</span>
                            </div>
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Total</p>
                                <span class="text-white fw-bold">£{{ number_format((float) $order->total, 2) }}</span>
                            </div>
                        </div>
                        <div>
                            @php
                                $statusBadge = match($order->status) {
                                    'delivered' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white',
                                    'shipping' => 'bg-primary text-white',
                                    'out_for_delivery' => 'bg-info text-white',
                                    default => 'bg-warning text-dark'
                                };
                            @endphp
                            <span class="badge {{ $statusBadge }} px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Body -->
                    <div class="card-body p-4">
                        <div class="row g-4 align-items-center">
                            <!-- Items List -->
                            <div class="col-md-8">
                                <h5 class="text-white-50 mb-3 small fw-bold text-uppercase">Items Ordered</h5>
                                <div class="d-flex flex-column gap-3">
                                    @foreach($order->items as $item)
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->meal && $item->meal->image_url)
                                                <img src="{{ $item->meal->image_url }}" alt="{{ $item->meal->title }}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-dark d-flex align-items-center justify-content-center border border-secondary" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-carrot text-emerald"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 text-white fw-bold">{{ $item->meal->title ?? 'Grocery Item' }}</h6>
                                                <p class="mb-0 text-muted small">Qty: {{ $item->quantity }} • Subtotal: £{{ number_format((float) $item->subtotal, 2) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Delivery Status Timeline -->
                            <div class="col-md-4 border-start" style="border-left-color: rgba(52, 211, 153, 0.1) !important;">
                                <h5 class="text-white-50 mb-3 small fw-bold text-uppercase">Shipping Address</h5>
                                @if($order->address)
                                    <div class="p-3 bg-dark rounded border border-emerald" style="background-color: rgba(0, 0, 0, 0.2) !important;">
                                        <p class="mb-1 fw-bold text-white small">{{ $order->address->full_name }}</p>
                                        <p class="mb-1 text-muted small">{{ $order->address->street_address }}, {{ $order->address->city }}</p>
                                        <p class="mb-0 text-muted small"><i class="fas fa-phone me-1"></i> {{ $order->address->phone }}</p>
                                    </div>
                                @else
                                    <p class="text-muted small">No shipping address details found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm text-white" style="background: #022c22; border: 1px solid rgba(52, 211, 153, 0.1) !important; border-radius: 16px;">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="fas fa-shopping-basket fs-1 mb-3 text-muted d-block" style="opacity: 0.3;"></i>
                        No orders placed yet on this account.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
