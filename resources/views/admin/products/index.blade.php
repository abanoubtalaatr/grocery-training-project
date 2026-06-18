@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">

```
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">المنتجات (Products)</h4>

                <a href="{{ route('products.create') }}"
                   class="btn btn-primary">
                    <i class="mdi mdi-plus"></i>
                    إضافة منتج جديد
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">

                <table class="table table-striped table-bordered align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>اسم المنتج</th>
                            <th>الفئة</th>
                            <th>السعر</th>
                            <th>المخزون</th>
                            <th>الحالة</th>
                            <th>الترتيب</th>
                            <th width="180">الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)

                            <tr>

                                <td>{{ $product->id }}</td>

                                <td>
                                    @if($product->image)
                                        <img
                                            src="{{ asset('storage/'.$product->image) }}"
                                            alt="{{ $product->name }}"
                                            width="60"
                                            height="60"
                                            style="object-fit:cover;border-radius:6px;">
                                    @else
                                        <span class="text-muted">لا توجد صورة</span>
                                    @endif
                                </td>

                                <td>{{ $product->name }}</td>

                                <td>
                                    {{ $product->category?->name }}
                                </td>

                                <td>
                                    {{ number_format($product->price,2) }}
                                </td>

                                <td>
                                    {{ $product->stock }}
                                </td>

                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">
                                            نشط
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            غير نشط
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $product->sort_order }}
                                </td>

                                <td>

                                    <a href="{{ route('products.edit',$product) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('products.destroy',$product) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('هل أنت متأكد من الحذف؟')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            <i class="mdi mdi-delete"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="text-center">
                                    لا توجد منتجات حالياً
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $products->links() }}
            </div>

        </div>

    </div>
</div>
```

</div>
@endsection
