@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">تفاصيل العنوان #{{ $address->id }}</h4>
                    <div>
                        <a href="{{ route('addresses.edit', $address) }}" class="btn btn-warning">تعديل</a>
                        <a href="{{ route('addresses.index') }}" class="btn btn-secondary">رجوع</a>
                    </div>
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th style="width: 220px;">المستخدم</th>
                        <td>{{ $address->user->name ?? '—' }} ({{ $address->user->email ?? '—' }})</td>
                    </tr>
                    <tr>
                        <th>اسم العنوان</th>
                        <td>{{ $address->label ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>الاسم الكامل</th>
                        <td>{{ $address->full_name }}</td>
                    </tr>
                    <tr>
                        <th>الهاتف</th>
                        <td>{{ $address->country_code }} {{ $address->phone }}</td>
                    </tr>
                    <tr>
                        <th>عنوان الشارع</th>
                        <td>{{ $address->street_address }}</td>
                    </tr>
                    <tr>
                        <th>المبنى / الطابق / الشقة</th>
                        <td>{{ $address->building_number ?? '—' }} / {{ $address->floor ?? '—' }} / {{ $address->apartment ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>علامة مميزة</th>
                        <td>{{ $address->landmark ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>المدينة / المحافظة</th>
                        <td>{{ $address->city }} / {{ $address->state ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>الرمز البريدي</th>
                        <td>{{ $address->postal_code ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>الدولة</th>
                        <td>{{ $address->country }}</td>
                    </tr>
                    <tr>
                        <th>الموقع (خط العرض / الطول)</th>
                        <td>{{ $address->latitude ?? '—' }} / {{ $address->longitude ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>ملاحظات</th>
                        <td>{{ $address->notes ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>عنوان افتراضي</th>
                        <td>
                            @if ($address->is_default)
                                <span class="badge badge-success">نعم</span>
                            @else
                                <span class="badge badge-secondary">لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>تاريخ الإضافة</th>
                        <td>{{ $address->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection