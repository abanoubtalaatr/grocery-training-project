@extends('admin.layouts.app')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">الأقسام</a></li>
        <li class="breadcrumb-item active">تعديل قسم</li>
    </ol>
@endsection

@section('content')
    <div class="card p-3">
        <h4>تعديل القسم</h4>
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">الاسم</label>
                <input name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">صورة</label>
                <input type="file" name="image" class="form-control">
                @if($category->image_url)
                    <div class="mt-2">
                        <img src="{{ $category->image_url }}" style="width:120px;height:80px;object-fit:cover;border-radius:4px">
                    </div>
                @endif
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">مفعل</label>
            </div>

            <button class="btn btn-primary">حفظ</button>
        </form>
    </div>
@endsection
