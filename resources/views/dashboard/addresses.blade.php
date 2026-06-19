@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 dash-text fw-bold">Manage Addresses</h2>
                    <p class="dash-text-muted mb-0">Add, edit, or configure your delivery locations.</p>
                </div>
                <button class="btn btn-emerald text-white fw-bold d-flex align-items-center gap-2 shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#createAddressModal" style="border-radius:8px;">
                    <i class="fas fa-plus"></i> Add Address
                </button>
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

            <!-- Addresses Grid -->
            <div class="row g-4">
                @forelse($addresses as $address)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm dash-card h-100">
                            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-emerald text-white px-2 py-1 rounded small fw-bold">
                                                <i class="fas fa-map-pin me-1"></i> {{ $address->label ?: 'Home' }}
                                            </span>
                                            @if($address->is_default)
                                                <span class="badge dash-inner dash-text-accent border px-2 py-1 rounded small" style="border-color:var(--dash-badge-border);">
                                                    Default
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm border-0 p-1 dash-text-muted" style="background:var(--dash-card-inner-bg);"
                                                    data-bs-toggle="modal" data-bs-target="#editAddressModal-{{ $address->id }}" title="Edit address">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('profile.addresses.destroy', $address->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Delete"
                                                        onclick="return confirm('Delete this address?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <h5 class="mb-1 dash-text fw-bold">{{ $address->full_name }}</h5>
                                    <p class="dash-text-subtle small mb-2"><i class="fas fa-phone me-1 dash-text-accent"></i>{{ $address->country_code }} {{ $address->phone }}</p>
                                    <p class="dash-text-muted small mb-4">
                                        {{ $address->street_address }}<br>
                                        @if($address->building_number) Building {{ $address->building_number }} @endif
                                        @if($address->floor) • Floor {{ $address->floor }} @endif
                                        @if($address->apartment) • Apt {{ $address->apartment }} @endif
                                        <br>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                        {{ $address->country }}
                                    </p>
                                </div>

                                @if(!$address->is_default)
                                    <div class="border-top pt-3" style="border-top-color: var(--dash-card-border) !important;">
                                        <form action="{{ route('profile.addresses.default', $address->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-emerald text-white w-100 fw-bold py-2" style="border-radius:8px;">
                                                Set as Default Address
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Edit Address Modal -->
                    <div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content dash-card">
                                <div class="modal-header border-0 pb-0" style="border-bottom-color:var(--dash-card-border);">
                                    <h5 class="modal-title fw-bold dash-text"><i class="fas fa-edit me-2 dash-text-accent"></i>Edit Address</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('profile.addresses.update', $address->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Label</label>
                                                <input type="text" class="form-control dash-input" name="label" value="{{ $address->label }}" placeholder="e.g. Home, Office" style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Receiver Full Name</label>
                                                <input type="text" class="form-control dash-input" name="full_name" value="{{ $address->full_name }}" required style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Country Code</label>
                                                <input type="text" class="form-control dash-input" name="country_code" value="{{ $address->country_code ?? '+20' }}" required style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Phone Number</label>
                                                <input type="text" class="form-control dash-input" name="phone" value="{{ $address->phone }}" required style="border-radius:8px;">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Street Address</label>
                                                <input type="text" class="form-control dash-input" name="street_address" value="{{ $address->street_address }}" required style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Building</label>
                                                <input type="text" class="form-control dash-input" name="building_number" value="{{ $address->building_number }}" style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Floor</label>
                                                <input type="text" class="form-control dash-input" name="floor" value="{{ $address->floor }}" style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Apartment</label>
                                                <input type="text" class="form-control dash-input" name="apartment" value="{{ $address->apartment }}" style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">City</label>
                                                <input type="text" class="form-control dash-input" name="city" value="{{ $address->city }}" required style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">State</label>
                                                <input type="text" class="form-control dash-input" name="state" value="{{ $address->state }}" style="border-radius:8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label dash-text-muted small fw-bold text-uppercase">Country</label>
                                                <input type="text" class="form-control dash-input" name="country" value="{{ $address->country }}" style="border-radius:8px;">
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="edit-is_default-{{ $address->id }}" name="is_default" value="1" {{ $address->is_default ? 'checked disabled' : '' }}>
                                                    <label class="form-check-label dash-text-muted small fw-bold" for="edit-is_default-{{ $address->id }}">Set as Default Address</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-secondary px-3 py-2" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                                        <button type="submit" class="btn btn-emerald text-white px-4 py-2" style="border-radius:8px;font-weight:600;">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm dash-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-map-marked-alt fs-1 mb-3 dash-text-muted d-block" style="opacity:0.4;"></i>
                                <p class="dash-text-muted mb-0">No addresses saved yet.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Create Address Modal -->
<div class="modal fade" id="createAddressModal" tabindex="-1" aria-labelledby="createAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content dash-card">
            <div class="modal-header border-0 pb-0" style="border-bottom-color:var(--dash-card-border);">
                <h5 class="modal-title fw-bold dash-text" id="createAddressModalLabel"><i class="fas fa-plus-circle me-2 dash-text-accent"></i>Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Label</label>
                            <input type="text" class="form-control dash-input" name="label" placeholder="e.g. Home, Office" style="border-radius:8px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Receiver Full Name</label>
                            <input type="text" class="form-control dash-input" name="full_name" required style="border-radius:8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Country Code</label>
                            <input type="text" class="form-control dash-input" name="country_code" value="+20" required style="border-radius:8px;">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Phone Number</label>
                            <input type="text" class="form-control dash-input" name="phone" required style="border-radius:8px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Street Address</label>
                            <input type="text" class="form-control dash-input" name="street_address" required style="border-radius:8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Building Number</label>
                            <input type="text" class="form-control dash-input" name="building_number" style="border-radius:8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Floor</label>
                            <input type="text" class="form-control dash-input" name="floor" style="border-radius:8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Apartment</label>
                            <input type="text" class="form-control dash-input" name="apartment" style="border-radius:8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">City</label>
                            <input type="text" class="form-control dash-input" name="city" required style="border-radius:8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">State</label>
                            <input type="text" class="form-control dash-input" name="state" style="border-radius:8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label dash-text-muted small fw-bold text-uppercase">Country</label>
                            <input type="text" class="form-control dash-input" name="country" value="Egypt" style="border-radius:8px;">
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                                <label class="form-check-label dash-text-muted small fw-bold" for="is_default">Set as Default Address</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary px-3 py-2" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-emerald text-white px-4 py-2" style="border-radius:8px;font-weight:600;">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
