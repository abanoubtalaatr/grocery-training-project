@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 dash-text fw-bold">Smart Lists</h2>
                    <p class="dash-text-muted mb-0">Manage your saved custom shopping & wish lists.</p>
                </div>
                <button class="btn btn-emerald text-white fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createListModal" style="border-radius:8px;">
                    <i class="fas fa-plus"></i> Create New List
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle text-success me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Smart Lists Grid -->
            <div class="row g-4">
                @forelse($smartLists as $list)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm dash-card h-100" style="overflow:hidden;">
                            <div class="card-body p-4 d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h4 class="mb-1 dash-text fw-bold">{{ $list->name }}</h4>
                                        @if($list->category)
                                            <span class="badge dash-inner dash-text-accent border px-2 py-1 small rounded" style="border-color: var(--dash-badge-border);">{{ $list->category }}</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('profile.smart-lists.destroy', $list->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Delete list" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <p class="dash-text-muted small mb-4 flex-grow-1">{{ $list->description ?: 'No description provided.' }}</p>

                                <div class="border-top pt-3 mt-auto" style="border-top-color: var(--dash-card-border) !important;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="dash-text-muted small"><i class="fas fa-utensils me-1"></i> {{ $list->meals->count() }} items</span>
                                        <span class="dash-text-subtle small">
                                            @if($list->notify_on_price_drop)<i class="fas fa-bell me-1 dash-text-accent" title="Price drop notifications active"></i> Price Drop@endif
                                            @if($list->notify_on_offers)<i class="fas fa-percentage ms-2 me-1 dash-text-accent" title="Offers notifications active"></i> Offers@endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm dash-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-list-ul fs-1 mb-3 dash-text-muted d-block" style="opacity:0.4;"></i>
                                <p class="dash-text-muted mb-0">No smart lists created yet.</p>
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
        <div class="modal-content dash-card">
            <div class="modal-header border-0 pb-0" style="border-bottom-color: var(--dash-card-border);">
                <h5 class="modal-title fw-bold dash-text" id="createListModalLabel"><i class="fas fa-plus-circle me-2 dash-text-accent"></i>Create Smart List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.smart-lists.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label dash-text-muted small fw-bold text-uppercase">List Name</label>
                        <input type="text" class="form-control dash-input" id="name" name="name" required style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label dash-text-muted small fw-bold text-uppercase">Category</label>
                        <input type="text" class="form-control dash-input" id="category" name="category" placeholder="e.g. Weekly Groceries" style="border-radius:8px;">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label dash-text-muted small fw-bold text-uppercase">Description</label>
                        <textarea class="form-control dash-input" id="description" name="description" rows="3" style="border-radius:8px;"></textarea>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_on_price_drop" name="notify_on_price_drop" value="1" checked>
                                <label class="form-check-label dash-text-muted small fw-bold" for="notify_on_price_drop">Price Drops</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_on_offers" name="notify_on_offers" value="1" checked>
                                <label class="form-check-label dash-text-muted small fw-bold" for="notify_on_offers">Offer Alerts</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary px-3 py-2" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-emerald text-white px-4 py-2" style="border-radius:8px; font-weight:600;">Create List</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
