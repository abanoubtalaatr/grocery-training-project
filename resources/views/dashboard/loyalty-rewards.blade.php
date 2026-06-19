@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 text-white font-weight-bold">Loyalty & Rewards</h2>
                    <p class="text-muted mb-0">Track your loyalty points, unlock tiers, and claim coupons.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-gem me-1"></i> Membership Club
                </div>
            </div>

            <!-- Stats/Summary cards -->
            <div class="row g-4 mb-4">
                <!-- Points balance -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #022c22 0%, #047857 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Points Balance</p>
                                <h3 class="mb-0 fw-bold text-emerald" style="font-size: 2.2rem;">{{ $loyaltyData['point_balance'] }} <span style="font-size: 1rem; font-weight: normal; color: rgba(255,255,255,0.7);">PTS</span></h3>
                            </div>
                            <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; border: 1px solid rgba(52, 211, 153, 0.15);">
                                <i class="fas fa-gift text-emerald fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reward Value -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #0f1a14 0%, #064e3b 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small fw-bold text-uppercase mb-1">Reward Value Equivalent</p>
                                <h3 class="mb-0 fw-bold text-white" style="font-size: 2.2rem;">£{{ number_format($loyaltyData['rewards_value'], 2) }}</h3>
                            </div>
                            <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; border: 1px solid rgba(52, 211, 153, 0.15);">
                                <i class="fas fa-pound-sign text-emerald fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tier Progress -->
            <div class="card border-0 shadow-sm text-white mb-4" style="background: #022c22; border: 1px solid rgba(52, 211, 153, 0.1) !important; border-radius: 16px;">
                <div class="card-body p-4">
                    <h4 class="mb-1 fw-bold text-white"><i class="fas fa-crown me-2 text-emerald"></i>Your Membership Status</h4>
                    <p class="text-muted small mb-4">You are currently in the <strong>{{ ucfirst($loyaltyData['benefits']['tier_name']) }}</strong> tier.</p>
                    
                    @php
                        $progressPercent = $loyaltyData['membership']['points_max'] > 0 
                            ? min(100, round(($loyaltyData['membership']['points_current'] / $loyaltyData['membership']['points_max']) * 100)) 
                            : 100;
                    @endphp

                    <div class="d-flex justify-content-between text-muted small fw-bold mb-1">
                        <span>{{ $loyaltyData['membership']['points_current'] }} PTS</span>
                        <span>{{ $loyaltyData['membership']['points_max'] }} PTS</span>
                    </div>
                    
                    <div class="progress bg-dark mb-3" style="height: 12px; border-radius: 6px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: {{ $progressPercent }}%; border-radius: 6px;" aria-valuenow="{{ $loyaltyData['membership']['points_current'] }}" aria-valuemin="0" aria-valuemax="{{ $loyaltyData['membership']['points_max'] }}"></div>
                    </div>

                    @if($loyaltyData['membership']['next_tier'])
                        <p class="text-white-50 mb-0 small"><i class="fas fa-chevron-circle-up text-emerald me-1"></i> You need <strong>{{ $loyaltyData['membership']['points_to_next'] }} more points</strong> to unlock <strong>{{ $loyaltyData['membership']['next_tier']['name'] }}</strong> status.</p>
                    @else
                        <p class="text-white-50 mb-0 small"><i class="fas fa-check-circle text-emerald me-1"></i> You have achieved the highest membership tier! Congratulations!</p>
                    @endif
                </div>
            </div>

            <!-- Tier Benefits & Active Coupons -->
            <div class="row g-4">
                <!-- Benefits -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm text-white h-100" style="background: linear-gradient(145deg, #022c22 0%, #0f1a14 100%); border: 1px solid rgba(52, 211, 153, 0.1) !important; border-radius: 16px;">
                        <div class="card-body p-4">
                            <h4 class="mb-3 fw-bold text-white"><i class="fas fa-list-check me-2 text-emerald"></i>{{ $loyaltyData['benefits']['tier_name'] }} Benefits</h4>
                            <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                                @forelse($loyaltyData['benefits']['items'] as $benefit)
                                    <li class="d-flex align-items-start gap-2">
                                        <i class="fas fa-check-circle text-emerald fs-5 mt-1"></i>
                                        <div>
                                            <strong class="text-white small d-block">{{ $benefit['title'] }}</strong>
                                            <span class="text-white-50 small">{{ $benefit['description'] }}</span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-muted small">No specific benefits defined for this tier.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Coupons -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm text-white h-100" style="background: linear-gradient(145deg, #022c22 0%, #064e3b 100%); border: 1px solid rgba(52, 211, 153, 0.1) !important; border-radius: 16px;">
                        <div class="card-body p-4">
                            <h4 class="mb-3 fw-bold text-white"><i class="fas fa-ticket-alt me-2 text-emerald"></i>Available Coupons</h4>
                            
                            <div class="d-flex flex-column gap-3" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                                @forelse($loyaltyData['coupons'] as $coupon)
                                    <div class="p-3 bg-dark rounded border border-emerald d-flex justify-content-between align-items-center" style="background-color: rgba(0, 0, 0, 0.2) !important;">
                                        <div>
                                            <span class="badge bg-emerald text-white px-2 py-1 rounded small mb-2">{{ $coupon['discount_label'] }}</span>
                                            <h6 class="mb-1 text-white fw-bold">{{ $coupon['title'] }}</h6>
                                            <p class="mb-0 text-muted small">{{ $coupon['description'] }}</p>
                                            <p class="mb-0 text-white-50 small mt-1"><i class="fas fa-clock me-1 text-emerald"></i>Expires: {{ $coupon['expires_label'] }}</p>
                                        </div>
                                        <div class="text-end">
                                            <code class="d-block bg-dark text-emerald px-3 py-2 rounded fw-bold border border-emerald mb-2" style="font-size: 0.95rem;">{{ $coupon['code'] }}</code>
                                            <button class="btn btn-sm btn-outline-emerald fw-bold py-1" onclick="navigator.clipboard.writeText('{{ $coupon['code'] }}'); alert('Code copied!')">
                                                Copy Code
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-3 bg-dark rounded border border-emerald text-center" style="background-color: rgba(0, 0, 0, 0.2) !important;">
                                        <p class="mb-0 text-muted small">No active coupons available at this time.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
