@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 dash-text fw-bold">Payment & Wallet</h2>
                    <p class="dash-text-muted mb-0">View your transaction history, wallet balances, and invoices.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-wallet me-1"></i> Billing & Finance
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm dash-card-gradient">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="dash-text-muted small fw-bold text-uppercase mb-1">Store Credits</p>
                                <h3 class="mb-0 fw-bold dash-text-accent" style="font-size:2.2rem;">
                                    £{{ number_format((float) ($user->store_credits ?? 0.00), 2) }}
                                </h3>
                            </div>
                            <div class="dash-icon-wrap shadow-sm" style="width:60px;height:60px;">
                                <i class="fas fa-wallet dash-text-accent fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm dash-card">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="dash-text-muted small fw-bold text-uppercase mb-1">Total Spent</p>
                                <h3 class="mb-0 fw-bold dash-text" style="font-size:2.2rem;">
                                    £{{ number_format((float) ($totalAmount ?? 0.00), 2) }}
                                </h3>
                            </div>
                            <div class="dash-icon-wrap shadow-sm" style="width:60px;height:60px;">
                                <i class="fas fa-chart-line dash-text-accent fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="card border-0 shadow-sm dash-card">
                <div class="card-header bg-transparent p-4 border-0 pb-0">
                    <h4 class="mb-1 fw-bold dash-text"><i class="fas fa-history me-2 dash-text-accent"></i>Transaction History</h4>
                    <p class="dash-text-muted mb-0 small">A list of all complete/active payments processed on your account.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle dash-table">
                            <thead class="dash-text-muted small text-uppercase" style="border-bottom: 1px solid var(--dash-card-border);">
                                <tr>
                                    <th class="ps-4 py-3">Order Number</th>
                                    <th class="py-3">Date</th>
                                    <th class="py-3">Method</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Items</th>
                                    <th class="pe-4 py-3 text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr style="border-bottom: 1px solid var(--dash-card-border);">
                                        <td class="ps-4 py-3 fw-bold dash-text">#{{ $order->order_number }}</td>
                                        <td class="py-3 dash-text-muted">{{ $order->placed_at?->format('M d, Y H:i') ?? $order->created_at?->format('M d, Y H:i') }}</td>
                                        <td class="py-3">
                                            <span class="badge dash-inner dash-text-muted border px-2 py-1 rounded" style="border-color:var(--dash-card-border);">
                                                <i class="fas fa-credit-card me-1 dash-text-accent"></i>
                                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $statusClass = match($order->status) {
                                                    'delivered' => 'bg-success text-white',
                                                    'cancelled' => 'bg-danger text-white',
                                                    'pending'   => 'bg-warning text-dark',
                                                    default     => 'bg-info text-white'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} px-2 py-1 rounded small">{{ ucfirst($order->status) }}</span>
                                        </td>
                                        <td class="py-3 dash-text-muted">{{ $order->items->sum('quantity') }} items</td>
                                        <td class="pe-4 py-3 text-end fw-bold dash-text-accent">£{{ number_format((float) $order->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 dash-text-muted">
                                            <i class="fas fa-receipt fs-1 mb-3 d-block" style="opacity:0.3;"></i>
                                            No transactions found on this account.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
