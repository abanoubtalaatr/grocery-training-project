@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 text-white font-weight-bold">Smart Lists</h2>
                    <p class="text-muted mb-0">Manage your saved custom shopping & wish lists.</p>
                </div>
                <button class="btn btn-emerald text-white fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createListModal" style="border-radius: 8px;">
                    <i class="fas fa-plus"></i> Create New List
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

            <!-- Smart Lists Grid -->
            <div class="row g-4">
                @forelse($smartLists as $list)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm text-white h-100" style="background: linear-gradient(145deg, #022c22 0%, #064e3b 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px; overflow: hidden;">
                            <div class="card-body p-4 d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h4 class="mb-1 text-white fw-bold">{{ $list->name }}</h4>
                                        @if($list->category)
                                            <span class="badge bg-dark text-emerald border border-emerald small rounded">{{ $list->category }}</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('profile.smart-lists.destroy', $list->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Delete list" onclick="return confirm('Are you sure you want to delete this list?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <p class="text-muted small mb-4 flex-grow-1">{{ $list->description ?: 'No description provided.' }}</p>

                                <div class="border-top pt-3 mt-auto" style="border-top-color: rgba(52, 211, 153, 0.1) !important;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted small"><i class="fas fa-utensils me-1"></i> {{ $list->meals->count() }} items</span>
                                        <span class="text-white-50 small">
                                            @if($list->notify_on_price_drop)
                                                <i class="fas fa-bell me-1 text-emerald" title="Price drop notifications active"></i> Price Drop
                                            @endif
                                            @if($list->notify_on_offers)
                                                <i class="fas fa-percentage ms-2 me-1 text-emerald" title="Offers notifications active"></i> Offers
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm text-white" style="background: #022c22; border: 1px solid rgba(52, 211, 153, 0.1) !important; border-radius: 16px;">
                            <div class="card-body text-center py-5 text-muted">
                                <i class="fas fa-list-ul fs-1 mb-3 text-muted d-block" style="opacity: 0.3;"></i>
                                No smart lists created yet.
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Create List Modal -->
<div class="modal fade" id="createListModal" tabindex="-1" aria-labelledby="createListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white" style="background: #022c22; border: 1px solid rgba(52, 211, 153, 0.2); border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-white" id="createListModalLabel"><i class="fas fa-plus-circle me-2 text-emerald"></i>Create Smart List</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="anonymous" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.smart-lists.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label text-muted small fw-bold text-uppercase">List Name</label>
                        <input type="text" class="form-control bg-dark border-0 text-white py-2" id="name" name="name" required style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label text-muted small fw-bold text-uppercase">Category</label>
                        <input type="text" class="form-control bg-dark border-0 text-white py-2" id="category" name="category" placeholder="e.g. Weekly Groceries, Healthy Meals" style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label text-muted small fw-bold text-uppercase">Description</label>
                        <textarea class="form-control bg-dark border-0 text-white py-2" id="description" name="description" rows="3" style="border-radius: 8px;"></textarea>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_on_price_drop" name="notify_on_price_drop" value="1" checked>
                                <label class="form-check-label text-white-50 small fw-bold" for="notify_on_price_drop">Price Drops</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_on_offers" name="notify_on_offers" value="1" checked>
                                <label class="form-check-label text-white-50 small fw-bold" for="notify_on_offers">Offer Alerts</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary px-3 py-2 border-0" data-bs-dismiss="modal" style="border-radius: 8px; background: rgba(255,255,255,0.08);">Cancel</button>
                    <button type="submit" class="btn btn-emerald text-white px-4 py-2" style="border-radius: 8px; font-weight: 600;">Create List</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
