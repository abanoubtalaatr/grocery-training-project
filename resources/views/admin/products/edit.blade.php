@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-body">

```
        <h4 class="card-title mb-4">تعديل المنتج</h4>

        <form action="{{ route('products.update',$product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">الفئة</label>

                <select name="category_id" class="form-control">

                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">اسم المنتج</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name',$product->name) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input
                    type="text"
                    name="slug"
                    class="form-control"
                    value="{{ old('slug',$product->slug) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea
                    name="description"
                    class="form-control"
                    rows="4">{{ old('description',$product->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">السعر</label>
                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        class="form-control"
                        value="{{ old('price',$product->price) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">المخزون</label>
                    <input
                        type="number"
                        name="stock"
                        class="form-control"
                        value="{{ old('stock',$product->stock) }}">
                </div>
            </div>

            @if($product->image)
                <div class="mt-3">
                    <img
                        src="{{ asset('storage/'.$product->image) }}"
                        width="120"
                        class="img-thumbnail">
                </div>
            @endif

            <div class="mt-3">
                <label class="form-label">تغيير الصورة</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mt-3">
                <label class="form-label">الترتيب</label>
                <input
                    type="number"
                    name="sort_order"
                    class="form-control"
                    value="{{ old('sort_order',$product->sort_order) }}">
            </div>

            <div class="form-check mt-3">
                <input
                    type="checkbox"
                    class="form-check-input"
                    name="is_active"
                    value="1"
                    {{ $product->is_active ? 'checked' : '' }}>

                <label class="form-check-label">
                    نشط
                </label>
            </div>

            <button type="submit" class="btn btn-warning mt-3">
                تحديث
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
