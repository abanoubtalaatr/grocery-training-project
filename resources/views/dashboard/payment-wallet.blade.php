@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 text-white font-weight-bold">Payment & Wallet</h2>
                    <p class="text-muted mb-0">View your transaction history, wallet balances, and invoices.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-wallet me-1"></i> Billing & Finance
                </div>
            </div>

            <!-- Stats/Wallet Cards -->
            <div class="row g-4 mb-4">
                <!-- Store Credit Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #022c22 0%, #047857 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Store Credits</p>
                                <h3 class="mb-0 fw-bold text-emerald" style="font-size: 2.2rem;">£{{ number_format((float) ($user->store_credits ?? 0.00), 2) }}</h3>
                            </div>
                            <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; border: 1px solid rgba(52, 211, 153, 0.15);">
                                <i class="fas fa-wallet text-emerald fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Spent Card -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #0f1a14 0%, #064e3b 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Total Spent</p>
                                <h3 class="mb-0 fw-bold text-white" style="font-size: 2.2rem;">£{{ number_format((float) ($totalAmount ?? 0.00), 2) }}</h3>
                            </div>
                            <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; border: 1px solid rgba(52, 211, 153, 0.15);">
                                <i class="fas fa-chart-line text-emerald fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Table Card -->
            <div class="card border-0 shadow-sm text-white" style="background: #022c22; border: 1px solid rgba(52, 211, 153, 0.1) !important; border-radius: 16px;">
                <div class="card-header bg-transparent p-4 border-0 pb-0">
                    <h4 class="mb-1 fw-bold text-white"><i class="fas fa-history me-2 text-emerald"></i>Transaction History</h4>
                    <p class="text-muted mb-0 small">A list of all complete/active payments processed on your account.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-dark table-borderless mb-0 align-middle">
                            <thead class="text-muted small text-uppercase" style="border-bottom: 1px solid rgba(52, 211, 153, 0.1);">
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
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.03); transition: background 0.2s;">
                                        <td class="ps-4 py-3 fw-bold text-white">
                                            #{{ $order->order_number }}
                                        </td>
                                        <td class="py-3 text-muted">
                                            {{ $order->placed_at?->format('M d, Y H:i') ?? $order->created_at?->format('M d, Y H:i') }}
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-dark text-white-50 border border-secondary px-2 py-1 rounded">
                                                <i class="fas fa-credit-card me-1 text-emerald"></i>
                                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $statusClass = match($order->status) {
                                                    'delivered' => 'bg-success text-white',
                                                    'cancelled' => 'bg-danger text-white',
                                                    'pending' => 'bg-warning text-dark',
                                                    default => 'bg-info text-white'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} px-2 py-1 rounded small">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-white-50">
                                            {{ $order->items->sum('quantity') }} items
                                        </td>
                                        <td class="pe-4 py-3 text-end fw-bold text-emerald">
                                            £{{ number_format((float) $order->total, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-receipt fs-1 mb-3 text-muted d-block" style="opacity: 0.3;"></i>
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
