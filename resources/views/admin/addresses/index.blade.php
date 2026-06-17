@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">{{ __('messages.addresses') ?? 'العناوين' }}</h4>
                    <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> إضافة عنوان جديد
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>الاسم الكامل</th>
                                <th>الهاتف</th>
                                <th>المدينة</th>
                                <th>الدولة</th>
                                <th>افتراضي</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($addresses as $address)
                                <tr>
                                    <td>{{ $address->id }}</td>
                                    <td>{{ $address->user->name ?? '—' }}</td>
                                    <td>{{ $address->full_name }}</td>
                                    <td>{{ $address->country_code }} {{ $address->phone }}</td>
                                    <td>{{ $address->city }}</td>
                                    <td>{{ $address->country }}</td>
                                    <td>
                                        @if ($address->is_default)
                                            <span class="badge badge-success">نعم</span>
                                        @else
                                            <span class="badge badge-secondary">لا</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('addresses.show', $address) }}" class="btn btn-sm btn-info">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-warning">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline" onsubmit="return confirm('متأكد من حذف هذا العنوان؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">لا توجد عناوين مسجلة حتى الآن.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $addresses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection