@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title mb-4">إضافة فئة جديدة</h4>

                <form action="{{ route('categories.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">اسم الفئة</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text"
                               name="slug"
                               class="form-control"
                               value="{{ old('slug') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="4">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الصورة</label>
                        <input type="file"
                               name="image"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number"
                               name="sort_order"
                               class="form-control"
                               value="0">
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox"
                               class="form-check-input"
                               name="is_active"
                               value="1"
                               checked>

                        <label class="form-check-label">
                            نشطة
                        </label>
                    </div>

                    <button type="submit" class="btn btn-success">
                        حفظ
                    </button>

                    <a href="{{ route('categories.index') }}"
                       class="btn btn-secondary">
                        رجوع
                    </a>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection