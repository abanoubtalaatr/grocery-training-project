@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 text-white font-weight-bold">Security & Settings</h2>
                    <p class="text-muted mb-0">Secure your account password and review active sessions.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-shield-alt me-1"></i> Account Security
                </div>
            </div>

            <!-- Success message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: rgba(16, 185, 129, 0.15); border-left: 4px solid #10b981 !important;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                        <span class="text-white">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Errors message -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: rgba(239, 68, 68, 0.15); border-left: 4px solid #ef4444 !important;">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-exclamation-triangle text-danger me-2 fs-5"></i>
                        <strong class="text-white">Validation Errors:</strong>
                    </div>
                    <ul class="mb-0 text-white-50 ps-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Change Password -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm text-white h-100" style="background: linear-gradient(145deg, #022c22 0%, #064e3b 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                        <div class="card-body p-4">
                            <h4 class="mb-3 fw-bold text-white"><i class="fas fa-lock me-2 text-emerald"></i>Update Password</h4>
                            
                            <form action="{{ route('profile.security.password') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="current_password" class="form-label text-muted small fw-bold text-uppercase">Current Password</label>
                                    <input type="password" class="form-control bg-dark border-0 text-white py-2" id="current_password" name="current_password" required style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label text-muted small fw-bold text-uppercase">New Password</label>
                                    <input type="password" class="form-control bg-dark border-0 text-white py-2" id="password" name="password" required style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label text-muted small fw-bold text-uppercase">Confirm New Password</label>
                                    <input type="password" class="form-control bg-dark border-0 text-white py-2" id="password_confirmation" name="password_confirmation" required style="border-radius: 8px;">
                                </div>

                                <button type="submit" class="btn btn-emerald text-white w-100 fw-bold py-2 mt-3" style="border-radius: 8px;">
                                    Change Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm text-white h-100" style="background: linear-gradient(145deg, #0f1a14 0%, #022c22 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                        <div class="card-body p-4">
                            <h4 class="mb-3 fw-bold text-white"><i class="fas fa-desktop me-2 text-emerald"></i>Active Sessions</h4>
                            <p class="text-muted small mb-4">Devices currently logged into your account.</p>

                            <div class="d-flex flex-column gap-3">
                                @forelse($sessions as $session)
                                    <div class="p-3 bg-dark rounded border border-emerald d-flex justify-content-between align-items-center" style="background-color: rgba(0, 0, 0, 0.2) !important;">
                                        <div>
                                            <h6 class="mb-1 text-white fw-bold"><i class="fas fa-mobile-alt me-2 text-emerald"></i>{{ $session->name }}</h6>
                                            <p class="mb-0 text-muted small">Created: {{ $session->created_at?->format('M d, Y H:i') }}</p>
                                            @if($session->last_used_at)
                                                <p class="mb-0 text-muted small">Last active: {{ $session->last_used_at?->diffForHumans() }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-3 bg-dark rounded border border-emerald text-center" style="background-color: rgba(0, 0, 0, 0.2) !important;">
                                        <p class="mb-0 text-muted small">No session tokens generated.</p>
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
