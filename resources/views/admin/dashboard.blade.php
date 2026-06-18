@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card p-3">
                    <h6>إجمالي الأقسام</h6>
                    <h2>{{ $categoriesCount }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6>إجمالي الطلبات</h6>
                    <h2>{{ $ordersCount }}</h2>
                </div>
            </div>

      
        </div>
        
            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card p-3">
                        <h5>آخر الأقسام</h5>
                        <div class="list-group list-group-flush">
                            @foreach($recentCategories as $c)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $c->image_url }}" style="width:64px;height:48px;object-fit:cover;border-radius:6px">
                                        <div>
                                            <div class="fw-bold">{{ $c->name }}</div>
                                            <small class="text-muted">{{ Str::limit($c->description, 60) }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-primary">تعديل</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>آخر الطلبات</h5>
                        <ul class="list-unstyled">
                            @foreach($recentOrders as $o)
                                <li class="mb-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $o->order_number }}</div>
                                        <small class="text-muted">{{ optional($o->user)->name ?? '—' }}</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-secondary badge-status">{{ $o->status }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
