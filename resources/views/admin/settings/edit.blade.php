@extends('admin.layouts.app')

@section('title', __('settings.title'))

@section('page-title', __('settings.title'))

@section('content')

<form
    method="POST"
    action="{{ route('admin.settings.update') }}">

    @csrf
    @method('PUT')

    {{-- General Settings --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            {{ __('settings.general_settings') }}
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>{{ __('settings.site_name') }}</label>

                    <input
                        type="text"
                        name="site_name"
                        class="form-control"
                        value="{{ old('site_name', $setting->site_name) }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label>{{ __('settings.site_description') }}</label>

                    <input
                        type="text"
                        name="site_description"
                        class="form-control"
                        value="{{ old('site_description', $setting->site_description) }}">

                </div>

            </div>

        </div>

    </div>

    {{-- Contact Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            {{ __('settings.contact_information') }}
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>{{ __('settings.email') }}</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $setting->email) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>{{ __('settings.phone') }}</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $setting->phone) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>{{ __('settings.support_email') }}</label>
                    <input
                        type="email"
                        name="support_email"
                        class="form-control"
                        value="{{ old('support_email', $setting->support_email) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>{{ __('settings.support_phone') }}</label>
                    <input
                        type="text"
                        name="support_phone"
                        class="form-control"
                        value="{{ old('support_phone', $setting->support_phone) }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>{{ __('settings.address') }}</label>
                    <textarea
                        name="address"
                        class="form-control">{{ old('address', $setting->address) }}</textarea>
                </div>

            </div>

        </div>

    </div>

    {{-- Social Media --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            {{ __('settings.social_media') }}
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <input type="text" name="facebook" class="form-control" placeholder="Facebook"
                        value="{{ old('facebook', $setting->facebook) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <input type="text" name="instagram" class="form-control" placeholder="Instagram"
                        value="{{ old('instagram', $setting->instagram) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <input type="text" name="linkedin" class="form-control" placeholder="LinkedIn"
                        value="{{ old('linkedin', $setting->linkedin) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <input type="text" name="twitter" class="form-control" placeholder="Twitter"
                        value="{{ old('twitter', $setting->twitter) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <input type="text" name="youtube" class="form-control" placeholder="YouTube"
                        value="{{ old('youtube', $setting->youtube) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <input type="text" name="whatsapp" class="form-control" placeholder="WhatsApp"
                        value="{{ old('whatsapp', $setting->whatsapp) }}">
                </div>

            </div>

        </div>

    </div>

    {{-- Store Settings --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            {{ __('settings.store_settings') }}
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <label>{{ __('settings.store_status') }}</label>

                    <select
                        name="store_status"
                        class="form-select">

                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="maintenance">Maintenance</option>

                    </select>

                </div>

                <div class="col-md-4">

                    <label>{{ __('settings.tax_rate') }}</label>

                    <input
                        type="number"
                        step="0.01"
                        name="tax_rate"
                        class="form-control"
                        value="{{ old('tax_rate', $setting->tax_rate) }}">

                </div>

                <div class="col-md-4">

                    <label>{{ __('settings.store_hours') }}</label>

                    <input
                        type="text"
                        name="store_hours"
                        class="form-control"
                        value="{{ old('store_hours', $setting->store_hours) }}">

                </div>

            </div>

            <div class="form-check mt-3">

                <input
                    class="form-check-input"
                    type="checkbox"
                    name="maintenance_mode"
                    value="1"
                    @checked($setting->maintenance_mode)>

                <label class="form-check-label">

                    {{ __('settings.maintenance_mode') }}

                </label>

            </div>

        </div>

    </div>

    {{-- Shipping --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            {{ __('settings.shipping_settings') }}
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <label>{{ __('settings.shipping_fee') }}</label>

                    <input
                        type="number"
                        step="0.01"
                        name="shipping_fee"
                        class="form-control"
                        value="{{ old('shipping_fee', $setting->shipping_fee) }}">

                </div>

                <div class="col-md-6">

                    <label>{{ __('settings.free_shipping_min_order') }}</label>

                    <input
                        type="number"
                        step="0.01"
                        name="free_shipping_min_order"
                        class="form-control"
                        value="{{ old('free_shipping_min_order', $setting->free_shipping_min_order) }}">

                </div>

            </div>

        </div>

    </div>

    {{-- Localization --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            {{ __('settings.localization') }}
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <label>{{ __('settings.currency_code') }}</label>

                    <input
                        type="text"
                        name="currency_code"
                        class="form-control"
                        value="{{ old('currency_code', $setting->currency_code) }}">

                </div>

                <div class="col-md-6">

                    <label>{{ __('settings.currency_symbol') }}</label>

                    <input
                        type="text"
                        name="currency_symbol"
                        class="form-control"
                        value="{{ old('currency_symbol', $setting->currency_symbol) }}">

                </div>

            </div>

        </div>

    </div>

    <button
        type="submit"
        class="btn btn-primary">

        {{ __('settings.save') }}

    </button>

</form>

@endsection