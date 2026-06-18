@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">إضافة عنوان جديد</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('addresses.store') }}" method="POST">
                    @csrf
@php
    $old = fn($field, $default = null) => old($field, $address?->$field ?? $default);
@endphp

<div class="row">
    
    <div class="col-md-6 form-group">
        <label>اسم العنوان (مثلاً: المنزل، العمل)</label>
        <input type="text" name="label" class="form-control" value="{{ $old('label') }}">
    </div>

    <div class="col-md-6 form-group">
        <label>الاسم الكامل <span class="text-danger">*</span></label>
        <input type="text" name="full_name" class="form-control" value="{{ $old('full_name') }}" required>
    </div>

    <div class="col-md-3 form-group">
        <label>كود الدولة</label>
        <input type="text" name="country_code" class="form-control" value="{{ $old('country_code', '+20') }}">
    </div>

    <div class="col-md-3 form-group">
        <label>رقم الهاتف <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control" value="{{ $old('phone') }}" required>
    </div>

    <div class="col-md-12 form-group">
        <label>عنوان الشارع <span class="text-danger">*</span></label>
        <input type="text" name="street_address" class="form-control" value="{{ $old('street_address') }}" required>
    </div>

    <div class="col-md-3 form-group">
        <label>رقم المبنى</label>
        <input type="text" name="building_number" class="form-control" value="{{ $old('building_number') }}">
    </div>

    <div class="col-md-3 form-group">
        <label>الطابق</label>
        <input type="text" name="floor" class="form-control" value="{{ $old('floor') }}">
    </div>

    <div class="col-md-3 form-group">
        <label>الشقة</label>
        <input type="text" name="apartment" class="form-control" value="{{ $old('apartment') }}">
    </div>

    <div class="col-md-3 form-group">
        <label>علامة مميزة (Landmark)</label>
        <input type="text" name="landmark" class="form-control" value="{{ $old('landmark') }}">
    </div>

    <div class="col-md-4 form-group">
        <label>المدينة <span class="text-danger">*</span></label>
        <input type="text" name="city" class="form-control" value="{{ $old('city') }}" required>
    </div>

    <div class="col-md-4 form-group">
        <label>المحافظة / الولاية</label>
        <input type="text" name="state" class="form-control" value="{{ $old('state') }}">
    </div>

    <div class="col-md-4 form-group">
        <label>الرمز البريدي</label>
        <input type="text" name="postal_code" class="form-control" value="{{ $old('postal_code') }}">
    </div>

    <div class="col-md-6 form-group">
        <label>الدولة <span class="text-danger">*</span></label>
        <input type="text" name="country" class="form-control" value="{{ $old('country', 'Egypt') }}" required>
    </div>

    <div class="col-md-3 form-group">
        <label>خط العرض (Latitude)</label>
        <input type="text" name="latitude" class="form-control" value="{{ $old('latitude') }}">
    </div>

    <div class="col-md-3 form-group">
        <label>خط الطول (Longitude)</label>
        <input type="text" name="longitude" class="form-control" value="{{ $old('longitude') }}">
    </div>

    <div class="col-md-12 form-group">
        <label>ملاحظات</label>
        <textarea name="notes" class="form-control" rows="3">{{ $old('notes') }}</textarea>
    </div>

</div>
                    <button type="submit" class="btn btn-primary">حفظ العنوان</button>
                    <a href="{{ route('addresses.index') }}" class="btn btn-secondary">رجوع</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection