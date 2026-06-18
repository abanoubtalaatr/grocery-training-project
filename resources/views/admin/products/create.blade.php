@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-body">

```
        <h4 class="card-title mb-4">إضافة منتج جديد</h4>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">الفئة</label>
                <select name="category_id" class="form-control">
                    <option value="">اختر الفئة</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">اسم المنتج</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">السعر</label>
                    <input type="number" step="0.01" name="price" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">المخزون</label>
                    <input type="number" name="stock" class="form-control">
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">الصورة</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mt-3">
                <label class="form-label">الترتيب</label>
                <input type="number" name="sort_order" value="0" class="form-control">
            </div>

            <div class="form-check mt-3">
                <input type="checkbox" class="form-check-input" name="is_active" value="1" checked>
                <label class="form-check-label">
                    نشط
                </label>
            </div>

            <button type="submit" class="btn btn-success mt-3">
                حفظ
            </button>

            <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">
                رجوع
            </a>

        </form>

    </div>
</div>
```

</div>
@endsection
