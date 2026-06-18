@extends('admin.layouts.app')

@section('content')
    @section('breadcrumb')
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
            <li class="breadcrumb-item active">الطلبات</li>
        </ol>
    @endsection

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>الطلبات</h4>
        <form method="GET" class="w-50">
            <div class="input-group">
                <input name="q" class="form-control" placeholder="بحث (رقم الطلب أو اسم المستخدم)" value="{{ $q ?? '' }}">
                <button class="btn btn-outline-secondary">بحث</button>
            </div>
        </form>
    </div>

    <div class="card p-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>المستخدم</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ optional($order->user)->name ?? '—' }}</td>
                        <td>{{ $order->total }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $orders->links() }}
    </div>
@endsection
