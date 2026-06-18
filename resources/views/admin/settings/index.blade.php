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
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">الإعدادات العامة</h5>
                <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary mb-3">تعديل الإعدادات</a>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>المفتاح</th>
                        <th>القيمة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rows = [
                            'site_name' => $settings->site_name,
                            'site_description' => $settings->site_description,

                            'email' => $settings->email,
                            'phone' => $settings->phone,
                            'support_email' => $settings->support_email,
                            'support_phone' => $settings->support_phone,
                            'address' => $settings->address,
                            'store_address' => $settings->store_address,

                            'facebook' => $settings->facebook,
                            'linkedin' => $settings->linkedin,
                            'instagram' => $settings->instagram,
                            'twitter' => $settings->twitter,
                            'whatsapp' => $settings->whatsapp,
                            'tiktok' => $settings->tiktok,
                            'snapchat' => $settings->snapchat,
                            'youtube' => $settings->youtube,

                            'copyright_text' => $settings->copyright_text,
                            'store_status' => $settings->store_status,
                            'maintenance_mode' => $settings->maintenance_mode ? 'Yes' : 'No',
                            'store_hours' => $settings->store_hours,
                            'currency_code' => $settings->currency_code,
                            'currency_symbol' => $settings->currency_symbol,
                            'tax_rate' => $settings->tax_rate,
                            'payment_methods' => is_array($settings->payment_methods) ? implode(', ', $settings->payment_methods) : ($settings->payment_methods ?? ''),
                            'shipping_note' => $settings->shipping_note,
                            'shipping_fee' => $settings->shipping_fee,
                            'free_shipping_min_order' => $settings->free_shipping_min_order,
                            'locale' => $settings->locale,
                            'timezone' => $settings->timezone,
                        ];
                    @endphp

                    @foreach($rows as $key => $value)
                        <tr>
                            <td class="text-start">{{ $key }}</td>
                            <td style="min-width:300px">{{ $value ?: '—' }}</td>
                            <td style="width:140px">—</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="text-start">الشعار</td>
                        <td>
                            @if($settings->logo)
                                <img src="{{ asset('storage/' . $settings->logo) }}" style="max-width:120px">
                            @else
                                —
                            @endif
                        </td>
                        <td>—</td>
                    </tr>

                    <tr>
                        <td class="text-start">favicon</td>
                        <td>
                            @if($settings->favicon)
                                <img src="{{ asset('storage/' . $settings->favicon) }}" style="max-width:48px">
                            @else
                                —
                            @endif
                        </td>
                        <td>—</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
