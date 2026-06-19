@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 dash-text fw-bold">Security & Settings</h2>
                    <p class="dash-text-muted mb-0">Secure your account password and review active sessions.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-shield-alt me-1"></i> Account Security
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle text-success me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <strong><i class="fas fa-exclamation-triangle text-danger me-2"></i>Validation Errors:</strong>
                    <ul class="mb-0 ps-4 mt-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Change Password -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm dash-card h-100">
                        <div class="card-body p-4">
                            <h4 class="mb-3 fw-bold dash-text"><i class="fas fa-lock me-2 dash-text-accent"></i>Update Password</h4>
                            <form action="{{ route('profile.security.password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="current_password" class="form-label dash-text-muted small fw-bold text-uppercase">Current Password</label>
                                    <input type="password" class="form-control dash-input" id="current_password" name="current_password" required style="border-radius:8px;">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label dash-text-muted small fw-bold text-uppercase">New Password</label>
                                    <input type="password" class="form-control dash-input" id="password" name="password" required style="border-radius:8px;">
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label dash-text-muted small fw-bold text-uppercase">Confirm New Password</label>
                                    <input type="password" class="form-control dash-input" id="password_confirmation" name="password_confirmation" required style="border-radius:8px;">
                                </div>
                                <button type="submit" class="btn btn-emerald text-white w-100 fw-bold py-2 mt-2" style="border-radius:8px;">
                                    Change Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm dash-card h-100">
                        <div class="card-body p-4">
                            <h4 class="mb-1 fw-bold dash-text"><i class="fas fa-desktop me-2 dash-text-accent"></i>Active Sessions</h4>
                            <p class="dash-text-muted small mb-4">Devices currently logged into your account.</p>
                            <div class="d-flex flex-column gap-3">
                                @forelse($sessions as $session)
                                    <div class="p-3 rounded dash-inner border d-flex justify-content-between align-items-center" style="border-color: var(--dash-card-border);">
                                        <div>
                                            <h6 class="mb-1 dash-text fw-bold"><i class="fas fa-mobile-alt me-2 dash-text-accent"></i>{{ $session->name }}</h6>
                                            <p class="mb-0 dash-text-muted small">Created: {{ $session->created_at?->format('M d, Y H:i') }}</p>
                                            @if($session->last_used_at)
                                                <p class="mb-0 dash-text-muted small">Last active: {{ $session->last_used_at?->diffForHumans() }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-3 rounded dash-inner border text-center" style="border-color: var(--dash-card-border);">
                                        <p class="mb-0 dash-text-muted small">No session tokens generated.</p>
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
