@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Welcome Header -->
            <div class="mb-4">
                <h2 class="h3 mb-1 dash-text fw-bold">Welcome back, {{ $user->firstname ?? 'Valued Customer' }}!</h2>
                <p class="dash-text-muted mb-0">Here is a quick overview of your grocery account, rewards, and activities today.</p>
            </div>

            <!-- Stats Grid -->
            <div class="row g-4 mb-4">
                <!-- Loyalty Points -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm dash-card-gradient h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white-50 mb-1 small fw-bold text-uppercase">Loyalty Points</p>
                                <h3 class="text-white fw-bold mb-0">{{ number_format($stats['loyalty_points']) }}</h3>
                            </div>
                            <div class="bg-white-10 rounded p-3 text-white">
                                <i class="fas fa-star fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Credits -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm dash-card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="dash-text-muted mb-1 small fw-bold text-uppercase">Store Credits</p>
                                <h3 class="dash-text fw-bold mb-0">£{{ number_format((float) $stats['store_credits'], 2) }}</h3>
                            </div>
                            <div class="dash-inner rounded p-3 border" style="border-color: var(--dash-card-border);">
                                <i class="fas fa-wallet fa-2x dash-text-accent"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm dash-card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="dash-text-muted mb-1 small fw-bold text-uppercase">Total Orders</p>
                                <h3 class="dash-text fw-bold mb-0">{{ $stats['orders_count'] }}</h3>
                            </div>
                            <div class="dash-inner rounded p-3 border" style="border-color: var(--dash-card-border);">
                                <i class="fas fa-shopping-bag fa-2x dash-text-accent"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Smart Lists -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm dash-card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="dash-text-muted mb-1 small fw-bold text-uppercase">Smart Lists</p>
                                <h3 class="dash-text fw-bold mb-0">{{ $stats['lists_count'] }}</h3>
                            </div>
                            <div class="dash-inner rounded p-3 border" style="border-color: var(--dash-card-border);">
                                <i class="fas fa-list-ul fa-2x dash-text-accent"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="row g-4">
                <!-- Recent Orders Column -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm dash-card mb-4">
                        <div class="card-header p-4 border-0 d-flex align-items-center justify-content-between dash-inner">
                            <h5 class="mb-0 fw-bold dash-text">Recent Orders</h5>
                            <a href="{{ route('profile.order-history') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                View All
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @if(count($recentOrders) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 dash-table">
                                        <thead>
                                            <tr class="dash-inner">
                                                <th class="ps-4 py-3 dash-text-muted small fw-bold text-uppercase">Order #</th>
                                                <th class="py-3 dash-text-muted small fw-bold text-uppercase">Date</th>
                                                <th class="py-3 dash-text-muted small fw-bold text-uppercase">Status</th>
                                                <th class="py-3 dash-text-muted small fw-bold text-uppercase">Total</th>
                                                <th class="pe-4 py-3 text-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentOrders as $order)
                                                <tr>
                                                    <td class="ps-4 py-3 fw-bold dash-text-accent">#{{ $order->order_number }}</td>
                                                    <td class="py-3 dash-text">{{ $order->placed_at?->format('M d, Y') ?? $order->created_at?->format('M d, Y') }}</td>
                                                    <td class="py-3">
                                                        @php
                                                            $statusBadge = match($order->status) {
                                                                'delivered'        => 'bg-success text-white',
                                                                'cancelled'        => 'bg-danger text-white',
                                                                'shipping'         => 'bg-primary text-white',
                                                                'out_for_delivery' => 'bg-info text-white',
                                                                default            => 'bg-warning text-dark'
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $statusBadge }} rounded-pill px-2 py-1 small text-uppercase" style="font-size: 0.7rem;">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 fw-bold dash-text">£{{ number_format((float) $order->total, 2) }}</td>
                                                    <td class="pe-4 py-3 text-end">
                                                        <a href="{{ route('profile.order-history') }}" class="btn btn-sm btn-link text-success p-0">
                                                            <i class="fas fa-chevron-right"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-basket fs-1 mb-3 dash-text-muted d-block animate-pulse" style="opacity: 0.4;"></i>
                                    <p class="dash-text-muted mb-0">No orders placed yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Quick Links & AI Panel -->
                <div class="col-lg-5">
                    <!-- Quick Actions Card -->
                    <div class="card border-0 shadow-sm dash-card mb-4">
                        <div class="card-header p-4 border-0 dash-inner">
                            <h5 class="mb-0 fw-bold dash-text">Quick Actions</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <a href="{{ route('profile.personal-info') }}" class="d-flex flex-column align-items-center justify-content-center p-3 rounded text-decoration-none text-center dash-card-alt border h-100" style="border-color: var(--dash-card-border); transition: transform 0.2s;">
                                        <i class="fas fa-user-cog fa-2x dash-text-accent mb-2"></i>
                                        <span class="dash-text small fw-bold">Edit Profile</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('profile.addresses') }}" class="d-flex flex-column align-items-center justify-content-center p-3 rounded text-decoration-none text-center dash-card-alt border h-100" style="border-color: var(--dash-card-border); transition: transform 0.2s;">
                                        <i class="fas fa-map-marked-alt fa-2x dash-text-accent mb-2"></i>
                                        <span class="dash-text small fw-bold">My Addresses</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('profile.security') }}" class="d-flex flex-column align-items-center justify-content-center p-3 rounded text-decoration-none text-center dash-card-alt border h-100" style="border-color: var(--dash-card-border); transition: transform 0.2s;">
                                        <i class="fas fa-user-shield fa-2x dash-text-accent mb-2"></i>
                                        <span class="dash-text small fw-bold">Security</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('profile.settings') }}" class="d-flex flex-column align-items-center justify-content-center p-3 rounded text-decoration-none text-center dash-card-alt border h-100" style="border-color: var(--dash-card-border); transition: transform 0.2s;">
                                        <i class="fas fa-sliders-h fa-2x dash-text-accent mb-2"></i>
                                        <span class="dash-text small fw-bold">Preferences</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AI Assistant Card -->
                    <div class="card border-0 shadow-sm dash-card-gradient text-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-white-10 rounded p-2.5">
                                    <i class="fas fa-robot fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-white">AI Assistant is Live</h6>
                                    <p class="text-white-50 mb-0 small">Powered by Grocery+ AI</p>
                                </div>
                            </div>
                            <p class="small text-white-80 mb-4 leading-relaxed">
                                Need help finding deals, browsing categories, or checking product prices? Ask your AI shopping assistant right now!
                            </p>
                            <a href="{{ route('chat') }}" target="_blank" class="btn btn-light rounded-pill w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                                <span>Start AI Conversation</span> <i class="fas fa-external-link-alt small"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
.dash-table tbody tr {
    transition: background-color 0.15s ease-in-out;
}
.dash-table tbody tr:hover {
    background-color: var(--dash-table-stripe);
}
.dash-card-alt:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
</style>
@endsection