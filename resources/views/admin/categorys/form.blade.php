
@php
    $old = fn($field, $default = null) => old($field, $category?->$field ?? $default);
@endphp

<div class="row">
    <div class="col-md-6 form-group">
        <label>اسم الفئة <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ $old('name') }}" required>
    </div>

    <div class="col-md-6 form-group">
        <label>الرابط (Slug)</label>
        <input type="text" name="slug" class="form-control" value="{{ $old('slug') }}" placeholder="سيتم إنشاؤه تلقائياً إذا تُرك فارغاً">
    </div>

    <div class="col-md-12 form-group">
        <label>الوصف</label>
        <textarea name="description" class="form-control" rows="4">{{ $old('description') }}</textarea>
    </div>

    <div class="col-md-6 form-group">
        <label>الصورة</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @if ($category?->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="80" style="border-radius: 4px;">
            </div>
        @endif
    </div>

    <div class="col-md-3 form-group">
        <label>الترتيب</label>
        <input type="number" name="sort_order" class="form-control" value="{{ $old('sort_order', 0) }}" min="0">
    </div>

    <div class="col-md-3 form-group">
        <label class="d-block">الحالة</label>
        <div class="form-check form-switch mt-2">
            <input type="checkbox" name="is_active" class="form-check-input" value="1"
                {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">نشطة</label>
        </div>
    </div>
</div>