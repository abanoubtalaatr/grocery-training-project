<div class="mb-3">

    <label class="form-label">
        Name
    </label>

    <input
        type="text"
        name="name"
        value="{{ old('name', $category->name ?? '') }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Description
    </label>

    <textarea
        name="description"
        rows="4"
        class="form-control">{{ old('description', $category->description ?? '') }}</textarea>

</div>

<div class="mb-3">

    <label class="form-label">
        Sort Order
    </label>

    <input
        type="number"
        name="sort_order"
        value="{{ old('sort_order', $category->sort_order ?? 0) }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Status
    </label>

    <select
        name="is_active"
        class="form-select">

        <option value="1"
            @selected(old('is_active', $category->is_active ?? 1) == 1)>
            Active
        </option>

        <option value="0"
            @selected(old('is_active', $category->is_active ?? 1) == 0)>
            Inactive
        </option>

    </select>

</div>

<button
    type="submit"
    class="btn btn-primary">

    Save

</button>

<a
    href="{{ route('admin.categories.index') }}"
    class="btn btn-secondary">

    Cancel

</a>