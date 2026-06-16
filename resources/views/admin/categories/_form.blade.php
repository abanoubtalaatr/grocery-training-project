<div class="mb-3">

    <label class="form-label">
        {{ __('general.name') }}
    </label>

    <input type="text"
           name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $category->name ?? '') }}">

    @error('name')

        <div class="invalid-feedback">
            {{ $message }}
        </div>

    @enderror

</div>
<div class="mb-3">

    <label class="form-label">

        {{ __('Description') }}

    </label>

    <textarea name="description"
              rows="4"
              class="form-control">{{ old('description', $category->description ?? '') }}</textarea>

</div>
<div class="mb-3">

    <label class="form-label">

        {{ __('Image') }}

    </label>

    <input type="file"
           name="image"
           class="form-control">

</div>
<div class="mb-3">

    <label class="form-label">

        {{ __('Sort Order') }}

    </label>

    <input type="number"
           name="sort_order"
           class="form-control"
           value="{{ old('sort_order', $category->sort_order ?? 0) }}">

</div>
<div class="form-check mb-4">

    <input type="checkbox"
           name="is_active"
           value="1"
           class="form-check-input"
           @checked(old('is_active', $category->is_active ?? true))>

    <label class="form-check-label">

        {{ __('Active') }}

    </label>

</div>
<button class="btn btn-primary">

    {{ $button }}

</button>

<a href="{{ route('admin.categories.index') }}"
   class="btn btn-secondary">

    {{ __('Cancel') }}

</a>