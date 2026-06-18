@extends('admin.layouts.app')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة الإدارة</a></li>
        <li class="breadcrumb-item active">الإعدادات</li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">اسم الموقع</label>
                    <input name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">وصف قصير</label>
                    <textarea name="site_description" class="form-control">{{ old('site_description', $settings->site_description) }}</textarea>
                </div>

                <h6 class="mt-3">معلومات الاتصال</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input name="email" class="form-control" value="{{ old('email', $settings->email) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الهاتف</label>
                        <input name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">بريد الدعم</label>
                        <input name="support_email" class="form-control" value="{{ old('support_email', $settings->support_email) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">هاتف الدعم</label>
                        <input name="support_phone" class="form-control" value="{{ old('support_phone', $settings->support_phone) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">العنوان</label>
                    <input name="address" class="form-control" value="{{ old('address', $settings->address) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">عنوان المتجر</label>
                    <input name="store_address" class="form-control" value="{{ old('store_address', $settings->store_address) }}">
                </div>

                <h6 class="mt-3">الوسائط</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">شعار الموقع</label>
                        <input type="file" name="logo" class="form-control">
                        @if($settings->logo)
                            <img src="{{ asset('storage/' . $settings->logo) }}" alt="logo" style="max-width:120px;margin-top:.5rem">
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">أيقونة الموقع (favicon)</label>
                        <input type="file" name="favicon" class="form-control">
                        @if($settings->favicon)
                            <img src="{{ asset('storage/' . $settings->favicon) }}" alt="favicon" style="max-width:64px;margin-top:.5rem">
                        @endif
                    </div>
                </div>

                <h6 class="mt-3">الشبكات الاجتماعية</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Facebook</label>
                        <input name="facebook" class="form-control" value="{{ old('facebook', $settings->facebook) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">LinkedIn</label>
                        <input name="linkedin" class="form-control" value="{{ old('linkedin', $settings->linkedin) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Instagram</label>
                        <input name="instagram" class="form-control" value="{{ old('instagram', $settings->instagram) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Twitter</label>
                        <input name="twitter" class="form-control" value="{{ old('twitter', $settings->twitter) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">WhatsApp</label>
                        <input name="whatsapp" class="form-control" value="{{ old('whatsapp', $settings->whatsapp) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">YouTube</label>
                        <input name="youtube" class="form-control" value="{{ old('youtube', $settings->youtube) }}">
                    </div>
                </div>

                <h6 class="mt-3">إعدادات المتجر</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">حالة المتجر</label>
                        <select name="store_status" class="form-select">
                            <option value="open" {{ old('store_status', $settings->store_status) === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ old('store_status', $settings->store_status) === 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="maintenance" {{ old('store_status', $settings->store_status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">وضع الصيانة</label>
                        <select name="maintenance_mode" class="form-select">
                            <option value="0" {{ !old('maintenance_mode', $settings->maintenance_mode) ? 'selected' : '' }}>Off</option>
                            <option value="1" {{ old('maintenance_mode', $settings->maintenance_mode) ? 'selected' : '' }}>On</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">ساعات العمل</label>
                        <input name="store_hours" class="form-control" value="{{ old('store_hours', $settings->store_hours) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">رمز العملة</label>
                        <input name="currency_code" class="form-control" value="{{ old('currency_code', $settings->currency_code) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">رمز العملة (الشكل)</label>
                        <input name="currency_symbol" class="form-control" value="{{ old('currency_symbol', $settings->currency_symbol) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">معدل الضريبة (%)</label>
                        <input name="tax_rate" type="number" step="0.01" class="form-control" value="{{ old('tax_rate', $settings->tax_rate) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">طرق الدفع (أدخل كل طريقة في سطر جديد)</label>
                    @php $methods = old('payment_methods', $settings->payment_methods ?? []); @endphp
                    <div id="paymentMethods">
                        @if(is_array($methods) && count($methods))
                            @foreach($methods as $m)
                                <input name="payment_methods[]" class="form-control mb-2" value="{{ $m }}">
                            @endforeach
                        @else
                            <input name="payment_methods[]" class="form-control mb-2" value="">
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-link" id="addPayment">إضافة طريقة</button>
                </div>

                <div class="mb-3">
                    <label class="form-label">ملاحظة الشحن</label>
                    <textarea name="shipping_note" class="form-control">{{ old('shipping_note', $settings->shipping_note) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">تكلفة الشحن</label>
                        <input name="shipping_fee" type="number" step="0.01" class="form-control" value="{{ old('shipping_fee', $settings->shipping_fee) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">حد الشحن المجاني</label>
                        <input name="free_shipping_min_order" type="number" step="0.01" class="form-control" value="{{ old('free_shipping_min_order', $settings->free_shipping_min_order) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">نص حقوق النشر</label>
                        <input name="copyright_text" class="form-control" value="{{ old('copyright_text', $settings->copyright_text) }}">
                    </div>
                </div>

                <h6 class="mt-3">اللغة والمنطقة</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اللغة (locale)</label>
                        <input name="locale" class="form-control" value="{{ old('locale', $settings->locale) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">المنطقة الزمنية (timezone)</label>
                        <input name="timezone" class="form-control" value="{{ old('timezone', $settings->timezone) }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    document.getElementById('addPayment')?.addEventListener('click', function(){
        const div = document.getElementById('paymentMethods');
        const input = document.createElement('input');
        input.name = 'payment_methods[]';
        input.className = 'form-control mb-2';
        div.appendChild(input);
    });
</script>
@endpush

@endsection

