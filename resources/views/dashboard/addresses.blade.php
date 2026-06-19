@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 text-white font-weight-bold">Manage Addresses</h2>
                    <p class="text-muted mb-0">Add, edit, or configure your delivery locations.</p>
                </div>
                <button class="btn btn-emerald text-white fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createAddressModal" style="border-radius: 8px;">
                    <i class="fas fa-plus"></i> Add Address
                </button>
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

            <!-- Addresses List -->
            <div class="row g-4">
                @forelse($addresses as $address)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm text-white h-100" style="background: linear-gradient(145deg, #022c22 0%, #064e3b 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-emerald text-white px-2 py-1 rounded smallfw-bold">
                                                <i class="fas fa-map-pin me-1"></i> {{ $address->label ?: 'Home' }}
                                            </span>
                                            @if($address->is_default)
                                                <span class="badge bg-dark text-emerald border border-emerald px-2 py-1 rounded small">
                                                    Default Address
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2">
                                            <!-- Edit Address button trigger -->
                                            <button class="btn btn-sm btn-outline-light border-0 p-1" data-bs-toggle="modal" data-bs-target="#editAddressModal-{{ $address->id }}" title="Edit address">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <form action="{{ route('profile.addresses.destroy', $address->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Delete address" onclick="return confirm('Are you sure you want to delete this address?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <h5 class="mb-1 text-white fw-bold">{{ $address->full_name }}</h5>
                                    <p class="text-white-50 small mb-2"><i class="fas fa-phone me-1 text-emerald"></i> {{ $address->country_code }} {{ $address->phone }}</p>
                                    
                                    <p class="text-muted small mb-4">
                                        {{ $address->street_address }}<br>
                                        @if($address->building_number) Building {{ $address->building_number }} @endif
                                        @if($address->floor) • Floor {{ $address->floor }} @endif
                                        @if($address->apartment) • Apt {{ $address->apartment }} @endif
                                        <br>
                                        {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                        {{ $address->country }}
                                    </p>
                                </div>

                                @if(!$address->is_default)
                                    <div class="border-top pt-3" style="border-top-color: rgba(52, 211, 153, 0.1) !important;">
                                        <form action="{{ route('profile.addresses.default', $address->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-emerald text-white w-100 fw-bold py-2" style="border-radius: 8px;">
                                                Set as Default Address
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    <!-- Edit Address Modal for each address -->
                    <div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content text-white" style="background: #022c22; border: 1px solid rgba(52, 211, 153, 0.2); border-radius: 16px;">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-white"><i class="fas fa-edit me-2 text-emerald"></i>Edit Address</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('profile.addresses.update', $address->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="row g-3">
                                            <!-- Label -->
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Label</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="label" value="{{ $address->label }}" placeholder="e.g. Home, Office" style="border-radius: 8px;">
                                            </div>
                                            <!-- Full Name -->
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Receiver Full Name</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="full_name" value="{{ $address->full_name }}" required style="border-radius: 8px;">
                                            </div>
                                            <!-- Country Code -->
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Country Code</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="country_code" value="{{ $address->country_code ?? '+20' }}" required style="border-radius: 8px;">
                                            </div>
                                            <!-- Phone -->
                                            <div class="col-md-8">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Phone Number</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="phone" value="{{ $address->phone }}" required style="border-radius: 8px;">
                                            </div>
                                            <!-- Street Address -->
                                            <div class="col-12">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Street Address</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="street_address" value="{{ $address->street_address }}" required style="border-radius: 8px;">
                                            </div>
                                            <!-- Building / Floor / Apt -->
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Building Number</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="building_number" value="{{ $address->building_number }}" style="border-radius: 8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Floor</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="floor" value="{{ $address->floor }}" style="border-radius: 8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Apartment</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="apartment" value="{{ $address->apartment }}" style="border-radius: 8px;">
                                            </div>
                                            <!-- City / State / Country -->
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold text-uppercase">City</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="city" value="{{ $address->city }}" required style="border-radius: 8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold text-uppercase">State</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="state" value="{{ $address->state }}" style="border-radius: 8px;">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted small fw-bold text-uppercase">Country</label>
                                                <input type="text" class="form-control bg-dark border-0 text-white py-2" name="country" value="{{ $address->country }}" style="border-radius: 8px;">
                                            </div>
                                            <!-- Default switch -->
                                            <div class="col-12 mt-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="edit-is_default-{{ $address->id }}" name="is_default" value="1" {{ $address->is_default ? 'checked disabled' : '' }}>
                                                    <label class="form-check-label text-white-50 small fw-bold" for="edit-is_default-{{ $address->id }}">Set as Default Address</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-secondary px-3 py-2 border-0" data-bs-dismiss="modal" style="border-radius: 8px; background: rgba(255,255,255,0.08);">Cancel</button>
                                        <button type="submit" class="btn btn-emerald text-white px-4 py-2" style="border-radius: 8px; font-weight: 600;">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm text-white" style="background: #022c22; border: 1px solid rgba(52, 211, 153, 0.1) !important; border-radius: 16px;">
                            <div class="card-body text-center py-5 text-muted">
                                <i class="fas fa-map-marked-alt fs-1 mb-3 text-muted d-block" style="opacity: 0.3;"></i>
                                No addresses saved.
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
        <div class="modal-content text-white" style="background: #022c22; border: 1px solid rgba(52, 211, 153, 0.2); border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-white" id="createAddressModalLabel"><i class="fas fa-plus-circle me-2 text-emerald"></i>Add Address</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Label -->
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Label</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="label" placeholder="e.g. Home, Office" style="border-radius: 8px;">
                        </div>
                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Receiver Full Name</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="full_name" required style="border-radius: 8px;">
                        </div>
                        <!-- Country Code -->
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Country Code</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="country_code" value="+20" required style="border-radius: 8px;">
                        </div>
                        <!-- Phone -->
                        <div class="col-md-8">
                            <label class="form-label text-muted small fw-bold text-uppercase">Phone Number</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="phone" required style="border-radius: 8px;">
                        </div>
                        <!-- Street Address -->
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold text-uppercase">Street Address</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="street_address" required style="border-radius: 8px;">
                        </div>
                        <!-- Building / Floor / Apt -->
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Building Number</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="building_number" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Floor</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="floor" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Apartment</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="apartment" style="border-radius: 8px;">
                        </div>
                        <!-- City / State / Country -->
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">City</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="city" required style="border-radius: 8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">State</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="state" style="border-radius: 8px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Country</label>
                            <input type="text" class="form-control bg-dark border-0 text-white py-2" name="country" value="Egypt" style="border-radius: 8px;">
                        </div>
                        <!-- Default switch -->
                        <div class="col-12 mt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                                <label class="form-check-label text-white-50 small fw-bold" for="is_default">Set as Default Address</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary px-3 py-2 border-0" data-bs-dismiss="modal" style="border-radius: 8px; background: rgba(255,255,255,0.08);">Cancel</button>
                    <button type="submit" class="btn btn-emerald text-white px-4 py-2" style="border-radius: 8px; font-weight: 600;">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
