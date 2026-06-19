@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 dash-text fw-bold">Personal Information</h2>
                    <p class="dash-text-muted mb-0">Manage your profile details and contact information.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-user-circle me-1"></i> Account Profile
                </div>
            </div>

            <!-- Success message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Errors message -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-exclamation-triangle text-danger me-2 fs-5"></i>
                        <strong>Please fix the following issues:</strong>
                    </div>
                    <ul class="mb-0 ps-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Main card -->
            <div class="card border-0 shadow-sm dash-card">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex flex-column flex-md-row align-items-center gap-4 mb-5 pb-4 border-bottom" style="border-bottom-color: var(--dash-card-border) !important;">
                        <div class="position-relative">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-lg"
                                 style="width: 100px; height: 100px; font-size: 2.5rem; background: linear-gradient(135deg, #34d399, #10b981); color: #022c22; border: 4px solid var(--dash-card-border);">
                                {{ strtoupper(substr($user->firstname ?? $user->username ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                        <div class="text-center text-md-start">
                            <h4 class="mb-1 dash-text fw-bold">{{ $user->full_name }}</h4>
                            <p class="dash-text-muted mb-2">{{ '@' . $user->username }}</p>
                            <span class="badge dash-inner dash-text-accent border px-2 py-1 rounded" style="border-color: var(--dash-badge-border); font-size: 0.75rem;">
                                Registered on {{ $user->created_at?->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('profile.personal-info.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Username -->
                            <div class="col-md-6">
                                <label for="username" class="form-label dash-text-muted small fw-bold text-uppercase">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text dash-input border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fas fa-at dash-text-muted"></i></span>
                                    <input type="text" class="form-control dash-input" id="username" name="username" value="{{ old('username', $user->username) }}" required style="border-radius: 0 8px 8px 0;">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label dash-text-muted small fw-bold text-uppercase">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text dash-input border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fas fa-envelope dash-text-muted"></i></span>
                                    <input type="email" class="form-control dash-input" id="email" name="email" value="{{ old('email', $user->email) }}" required style="border-radius: 0 8px 8px 0;">
                                </div>
                            </div>

                            <!-- First Name -->
                            <div class="col-md-6">
                                <label for="firstname" class="form-label dash-text-muted small fw-bold text-uppercase">First Name</label>
                                <input type="text" class="form-control dash-input" id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" required style="border-radius: 8px;">
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <label for="lastname" class="form-label dash-text-muted small fw-bold text-uppercase">Last Name</label>
                                <input type="text" class="form-control dash-input" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" required style="border-radius: 8px;">
                            </div>

                            <!-- Country Code -->
                            <div class="col-md-4">
                                <label for="country_code" class="form-label dash-text-muted small fw-bold text-uppercase">Country Code</label>
                                <div class="input-group">
                                    <span class="input-group-text dash-input border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fas fa-globe dash-text-muted"></i></span>
                                    <input type="text" class="form-control dash-input" id="country_code" name="country_code" value="{{ old('country_code', $user->country_code ?? '+20') }}" required placeholder="+20" style="border-radius: 0 8px 8px 0;">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-8">
                                <label for="phone" class="form-label dash-text-muted small fw-bold text-uppercase">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text dash-input border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fas fa-phone dash-text-muted"></i></span>
                                    <input type="text" class="form-control dash-input" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required style="border-radius: 0 8px 8px 0;">
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <label for="gender" class="form-label dash-text-muted small fw-bold text-uppercase">Gender</label>
                                <select class="form-select dash-input" id="gender" name="gender" style="border-radius: 8px;">
                                    <option value="" {{ old('gender', $user->gender) == '' ? 'selected' : '' }}>Select Gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                </select>
                            </div>

                            <!-- Birthday -->
                            <div class="col-md-6">
                                <label for="birthday" class="form-label dash-text-muted small fw-bold text-uppercase">Birthday</label>
                                <div class="input-group">
                                    <span class="input-group-text dash-input border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fas fa-calendar-alt dash-text-muted"></i></span>
                                    <input type="date" class="form-control dash-input" id="birthday" name="birthday" value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}" style="border-radius: 0 8px 8px 0;">
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="d-flex justify-content-end mt-5 pt-3 border-top" style="border-top-color: var(--dash-card-border) !important;">
                            <button type="submit" class="btn btn-emerald px-4 py-2 text-white shadow-sm d-flex align-items-center gap-2" style="border-radius: 8px; font-weight: 600;">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
