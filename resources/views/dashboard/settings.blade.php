@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Header section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1 text-white font-weight-bold">Account Preferences</h2>
                    <p class="text-muted mb-0">Customize your notification preferences, theme, and language.</p>
                </div>
                <div class="badge bg-emerald px-3 py-2 rounded-pill text-white shadow-sm">
                    <i class="fas fa-cog me-1"></i> User Preferences
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

            <form action="{{ route('profile.settings.update') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- App Preferences (Theme & Language) -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm text-white h-100" style="background: linear-gradient(145deg, #022c22 0%, #064e3b 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                            <div class="card-body p-4">
                                <h4 class="mb-3 fw-bold text-white"><i class="fas fa-adjust me-2 text-emerald"></i>Interface Settings</h4>
                                
                                <!-- Language -->
                                <div class="mb-4">
                                    <label for="language" class="form-label text-muted small fw-bold text-uppercase">Preferred Language</label>
                                    <select class="form-select bg-dark border-0 text-white py-2" id="language" name="language" style="border-radius: 8px;">
                                        <option value="en" {{ old('language', $language) == 'en' ? 'selected' : '' }}>English (United Kingdom)</option>
                                        <option value="ar" {{ old('language', $language) == 'ar' ? 'selected' : '' }}>العربية (Arabic)</option>
                                    </select>
                                    <p class="text-muted small mt-1 mb-0">Changes the primary display language of the app.</p>
                                </div>

                                <!-- Theme -->
                                <div class="mb-3">
                                    <label for="theme" class="form-label text-muted small fw-bold text-uppercase">App Theme</label>
                                    <select class="form-select bg-dark border-0 text-white py-2" id="theme" name="theme" style="border-radius: 8px;">
                                        <option value="light" {{ old('theme', $theme) == 'light' ? 'selected' : '' }}>Light Mode</option>
                                        <option value="dark" {{ old('theme', $theme) == 'dark' ? 'selected' : '' }}>Dark Mode</option>
                                    </select>
                                    <p class="text-muted small mt-1 mb-0">Choose between a light fresh appearance or dark mode.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Toggles -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm text-white h-100" style="background: linear-gradient(145deg, #0f1a14 0%, #022c22 100%); border: 1px solid rgba(52, 211, 153, 0.12) !important; border-radius: 16px;">
                            <div class="card-body p-4">
                                <h4 class="mb-3 fw-bold text-white"><i class="fas fa-bell me-2 text-emerald"></i>Notification Settings</h4>
                                <p class="text-muted small mb-4">Choose which notifications you wish to receive.</p>

                                <div class="d-flex flex-column gap-3">
                                    <!-- Order Updates -->
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="order_updates" name="order_updates" value="1" {{ old('order_updates', $preferences['order_updates'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label text-white fw-bold small" for="order_updates">Order Updates</label>
                                        <p class="text-muted small mb-0">Get notifications about your order placement and status updates.</p>
                                    </div>

                                    <!-- Promotion Emails -->
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="promotion_emails" name="promotion_emails" value="1" {{ old('promotion_emails', $preferences['promotion_emails'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label text-white fw-bold small" for="promotion_emails">Promotional Emails</label>
                                        <p class="text-muted small mb-0">Receive email alerts for new offers, coupon codes, and campaigns.</p>
                                    </div>

                                    <!-- Nutrition Insights -->
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="nutrition_insights" name="nutrition_insights" value="1" {{ old('nutrition_insights', $preferences['nutrition_insights'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label text-white fw-bold small" for="nutrition_insights">Nutrition Insights</label>
                                        <p class="text-muted small mb-0">Receive healthy eating tips and analysis from our nutritionist partners.</p>
                                    </div>

                                    <!-- Price Alerts -->
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="price_alerts" name="price_alerts" value="1" {{ old('price_alerts', $preferences['price_alerts'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label text-white fw-bold small" for="price_alerts">Price Drop Alerts</label>
                                        <p class="text-muted small mb-0">Get alerts when products in your smart lists decrease in price.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-emerald text-white fw-bold px-4 py-2 shadow-sm d-flex align-items-center gap-2" style="border-radius: 8px;">
                        <i class="fas fa-save"></i> Save Preferences
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
