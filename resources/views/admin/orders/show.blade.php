@extends('admin.layouts.app')

@section('content')
    <div class="card p-3">
        @section('breadcrumb')
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">الطلبات</a></li>
                <li class="breadcrumb-item active">{{ $order->order_number }}</li>
            </ol>
        @endsection

        <h4>طلب {{ $order->order_number }}</h4>

        <div class="row mb-3">
            <div class="col-md-6">
                <div><strong>المستخدم:</strong> {{ optional($order->user)->name ?? '—' }}</div>
                <div><strong>العنوان:</strong> {{ optional($order->address)->address ?? '—' }}</div>
            </div>
            <div class="col-md-6 text-start">
                <div><strong>المجموع:</strong> {{ $order->total }}</div>
                <div><strong>الحالة:</strong> {{ $order->status }}</div>
            </div>
        </div>

        <h5>بنود الطلب</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->name ?? ($item->meal?->name ?? '—') }}</td>
                        <td>{{ $item->quantity ?? 1 }}</td>
                        <td>{{ $item->price ?? $item->total ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h5>ملاحظات</h5>
        <div class="mb-3">{{ $order->notes ?? '—' }}</div>

        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <input name="status" class="form-control" value="{{ old('status', $order->status) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control">{{ old('notes', $order->notes) }}</textarea>
            </div>
            <button class="btn btn-primary">حفظ</button>
        </form>
    </div>
@endsection
