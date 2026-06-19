@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 dash-text fw-bold">Account Preferences</h2>
                    <p class="dash-text-muted mb-0">Customize your notification preferences, theme, and language.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-cog me-1"></i> User Preferences
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle text-success me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('profile.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <!-- Interface Settings -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm dash-card h-100">
                            <div class="card-body p-4">
                                <h4 class="mb-3 fw-bold dash-text"><i class="fas fa-adjust me-2 dash-text-accent"></i>Interface Settings</h4>

                                <div class="mb-4">
                                    <label for="language" class="form-label dash-text-muted small fw-bold text-uppercase">Preferred Language</label>
                                    <select class="form-select dash-input" id="language" name="language" style="border-radius:8px;">
                                        <option value="en" {{ old('language', $language) == 'en' ? 'selected' : '' }}>English (United Kingdom)</option>
                                        <option value="ar" {{ old('language', $language) == 'ar' ? 'selected' : '' }}>العربية (Arabic)</option>
                                    </select>
                                    <p class="dash-text-muted small mt-1 mb-0">Changes the primary display language of the app.</p>
                                </div>

                                <div class="mb-3">
                                    <label for="theme" class="form-label dash-text-muted small fw-bold text-uppercase">App Theme</label>
                                    <select class="form-select dash-input" id="theme" name="theme" style="border-radius:8px;">
                                        <option value="light" {{ old('theme', $theme) == 'light' ? 'selected' : '' }}>Light Mode</option>
                                        <option value="dark" {{ old('theme', $theme) == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                                    </select>
                                    <p class="dash-text-muted small mt-1 mb-0">Choose between a light fresh appearance or dark mode.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm dash-card h-100">
                            <div class="card-body p-4">
                                <h4 class="mb-1 fw-bold dash-text"><i class="fas fa-bell me-2 dash-text-accent"></i>Notification Settings</h4>
                                <p class="dash-text-muted small mb-4">Choose which notifications you wish to receive.</p>

                                <div class="d-flex flex-column gap-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="order_updates" name="order_updates" value="1" {{ old('order_updates', $preferences['order_updates'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label dash-text fw-bold small" for="order_updates">Order Updates</label>
                                        <p class="dash-text-muted small mb-0">Get notifications about your order placement and status updates.</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="promotion_emails" name="promotion_emails" value="1" {{ old('promotion_emails', $preferences['promotion_emails'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label dash-text fw-bold small" for="promotion_emails">Promotional Emails</label>
                                        <p class="dash-text-muted small mb-0">Receive email alerts for new offers, coupon codes, and campaigns.</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="nutrition_insights" name="nutrition_insights" value="1" {{ old('nutrition_insights', $preferences['nutrition_insights'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label dash-text fw-bold small" for="nutrition_insights">Nutrition Insights</label>
                                        <p class="dash-text-muted small mb-0">Receive healthy eating tips and analysis from our nutritionist partners.</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="price_alerts" name="price_alerts" value="1" {{ old('price_alerts', $preferences['price_alerts'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label dash-text fw-bold small" for="price_alerts">Price Drop Alerts</label>
                                        <p class="dash-text-muted small mb-0">Get alerts when products in your smart lists decrease in price.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-emerald text-white fw-bold px-4 py-2 shadow-sm d-flex align-items-center gap-2" style="border-radius:8px;">
                        <i class="fas fa-save"></i> Save Preferences
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
