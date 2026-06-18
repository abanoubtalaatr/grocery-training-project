@extends('admin.layouts.app')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">الأقسام</a></li>
        <li class="breadcrumb-item active">إضافة قسم</li>
    </ol>
@endsection

@section('content')
    <div class="card p-3">
        <h4>إضافة قسم</h4>
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.categories.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">الاسم</label>
                <input name="name" value="{{ old('name') }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">صورة</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">مفعل</label>
            </div>

            <button class="btn btn-primary">حفظ</button>
        </form>
    </div>
@endsection
