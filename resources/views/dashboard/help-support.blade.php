@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 dash-text fw-bold">Help & Support</h2>
                    <p class="dash-text-muted mb-0">Submit a support request or view your ticket history.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-question-circle me-1"></i> Customer Support
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
                <!-- Submit Ticket Form -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm dash-card h-100">
                        <div class="card-body p-4">
                            <h4 class="mb-3 fw-bold dash-text"><i class="fas fa-paper-plane me-2 dash-text-accent"></i>Submit Request</h4>
                            <form action="{{ route('profile.help-support.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="issue_type" class="form-label dash-text-muted small fw-bold text-uppercase">Issue Type</label>
                                    <select class="form-select dash-input" id="issue_type" name="issue_type" required style="border-radius:8px;">
                                        <option value="">Select Topic</option>
                                        <option value="order_delivery"   {{ old('issue_type') == 'order_delivery'   ? 'selected' : '' }}>Order & Delivery</option>
                                        <option value="payment_billing"  {{ old('issue_type') == 'payment_billing'  ? 'selected' : '' }}>Payment & Billing</option>
                                        <option value="account_settings" {{ old('issue_type') == 'account_settings' ? 'selected' : '' }}>Account Settings</option>
                                        <option value="technical_issue"  {{ old('issue_type') == 'technical_issue'  ? 'selected' : '' }}>Technical Issue</option>
                                        <option value="other"            {{ old('issue_type') == 'other'            ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="order_number" class="form-label dash-text-muted small fw-bold text-uppercase">Order Number (Optional)</label>
                                    <input type="text" class="form-control dash-input" id="order_number" name="order_number" value="{{ old('order_number') }}" placeholder="e.g. ORD-123456" style="border-radius:8px;">
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label dash-text-muted small fw-bold text-uppercase">Message Details</label>
                                    <textarea class="form-control dash-input" id="message" name="message" rows="5" placeholder="Describe your problem in detail..." required style="border-radius:8px;">{{ old('message') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-emerald text-white w-100 fw-bold py-2 mt-2" style="border-radius:8px;">
                                    Send Support Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Ticket History -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm dash-card h-100">
                        <div class="card-body p-4">
                            <h4 class="mb-1 fw-bold dash-text"><i class="fas fa-history me-2 dash-text-accent"></i>Request History</h4>
                            <p class="dash-text-muted small mb-4">Past support messages submitted from this account.</p>

                            <div class="d-flex flex-column gap-3" style="max-height:400px;overflow-y:auto;padding-right:5px;">
                                @forelse($reports as $report)
                                    <div class="p-3 rounded dash-inner border" style="border-color: var(--dash-card-border);">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-emerald text-white px-2 py-1 rounded small fw-bold">{{ ucfirst(str_replace('_', ' ', $report->issue_type)) }}</span>
                                            <span class="dash-text-muted small">{{ $report->created_at?->diffForHumans() }}</span>
                                        </div>
                                        @if($report->order_number)
                                            <p class="mb-2 dash-text-subtle small"><i class="fas fa-receipt dash-text-accent me-1"></i>Order: <strong>{{ $report->order_number }}</strong></p>
                                        @endif
                                        <p class="mb-0 dash-text-muted small" style="white-space:pre-wrap;">{{ $report->message }}</p>
                                    </div>
                                @empty
                                    <div class="p-3 rounded dash-inner border text-center" style="border-color: var(--dash-card-border);">
                                        <p class="mb-0 dash-text-muted small">No past support requests submitted.</p>
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
